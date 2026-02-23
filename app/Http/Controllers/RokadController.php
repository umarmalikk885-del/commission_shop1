<?php

namespace App\Http\Controllers;

use App\Models\Laga;
use App\Models\LagaAdvance;
use App\Models\LagaPayment;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RokadController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $allLagaNames = Laga::select('name', 'code')->get();
        $query = Laga::query();

        // Filter by search term (name, code, mobile)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('name_search')) {
            $query->where('name', 'like', "%{$request->name_search}%");
        }
        
        if ($request->filled('code_search')) {
            $query->where('code', 'like', "%{$request->code_search}%");
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'name');
        $sortOrder = $request->input('sort_order', 'asc');
        
        if (in_array($sortBy, ['name', 'code', 'mobile', 'id', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        // General List Export
        if ($request->has('export') && $request->export != 'ledger') {
            $allLagas = $query->get();
            foreach ($allLagas as $laga) {
                // For export, we also respect date range for the summary stats
                $purchasesQuery = $laga->purchases();
                $paymentsQuery = $laga->payments();
                
                if ($request->filled('start_date')) {
                    $purchasesQuery->where('purchase_date', '>=', $request->start_date);
                    $paymentsQuery->where('payment_date', '>=', $request->start_date);
                }
                if ($request->filled('end_date')) {
                    $purchasesQuery->where('purchase_date', '<=', $request->end_date);
                    $paymentsQuery->where('payment_date', '<=', $request->end_date);
                }

                $totalPurchaseAmount = $purchasesQuery->sum(DB::raw('total_amount + commission_amount'));
                $totalInstantPaid = $purchasesQuery->sum('paid_amount');
                $totalLaterPaid = $paymentsQuery->sum('amount');
                
                $laga->total_dues = $totalPurchaseAmount;
                $laga->total_paid = $totalInstantPaid + $totalLaterPaid;
                $laga->balance = $laga->total_dues - $laga->total_paid;
            }

            return response()->streamDownload(function() use ($allLagas, $request) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                $headers = ['کوڈ', 'نام', 'موبائل', 'کل واجبات', 'کل ادا شدہ', 'بیلنس', 'اسٹیٹس'];
                if ($request->filled('start_date') || $request->filled('end_date')) {
                    $headers[3] .= ' (مدت)';
                    $headers[4] .= ' (مدت)';
                    $headers[5] .= ' (مدت)';
                }
                fputcsv($file, $headers);
                
                foreach ($allLagas as $laga) {
                    fputcsv($file, [
                        $laga->code,
                        $laga->name,
                        $laga->mobile,
                        number_format($laga->total_dues, 2),
                        number_format($laga->total_paid, 2),
                        number_format($laga->balance, 2),
                        $laga->balance <= 0 ? 'ادا شدہ' : ($laga->total_paid > 0 ? 'جزوی' : 'باقی')
                    ]);
                }
                fclose($file);
            }, 'dues_report_' . date('Y-m-d') . '.csv');
        }

        $lagas = $query->paginate(10);
        $purchasers = $lagas;
        $allPurchaserNames = $allLagaNames;

        // Calculate balances for each laga (Period specific if dates provided)
        foreach ($lagas as $laga) {
            $purchasesQuery = $laga->purchases();
            $paymentsQuery = $laga->payments();
            $advancesQuery = $laga->advances();
            
            if ($request->filled('start_date')) {
                $purchasesQuery->where('purchase_date', '>=', $request->start_date);
                $paymentsQuery->where('payment_date', '>=', $request->start_date);
                $advancesQuery->where('advance_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $purchasesQuery->where('purchase_date', '<=', $request->end_date);
                $paymentsQuery->where('payment_date', '<=', $request->end_date);
                $advancesQuery->where('advance_date', '<=', $request->end_date);
            }
            
            $totalPurchaseAmount = $purchasesQuery->sum(DB::raw('total_amount + commission_amount'));
            $totalInstantPaid = $purchasesQuery->sum('paid_amount');
            $totalLaterPaid = $paymentsQuery->sum('amount');
            $totalAdvance = $advancesQuery->sum('amount');
            
            $laga->total_advance = $totalAdvance;
            $laga->total_dues = $totalPurchaseAmount + $totalAdvance;
            $laga->total_paid = $totalInstantPaid + $totalLaterPaid;
            $laga->balance = $laga->total_dues - $laga->total_paid;
        }

        $transactions = collect();
        $openingBalance = 0;
        $closingBalance = 0;

        if (($request->filled('search') || $request->filled('name_search') || $request->filled('code_search')) && $lagas->count() === 1) {
            $laga = $lagas->first();
            
            // Calculate Opening Balance
            if ($request->filled('start_date')) {
                $prevPurchases = $laga->purchases()->where('purchase_date', '<', $request->start_date)->sum(DB::raw('total_amount + commission_amount'));
                $prevInstant = $laga->purchases()->where('purchase_date', '<', $request->start_date)->sum('paid_amount');
                $prevPayments = $laga->payments()->where('payment_date', '<', $request->start_date)->sum('amount');
                $prevAdvances = $laga->advances()->where('advance_date', '<', $request->start_date)->sum('amount');
                $openingBalance = $prevPurchases + $prevAdvances - ($prevInstant + $prevPayments);
            }
            
            $purchases = $laga->purchases()
                ->select('id', 'purchase_date as date', DB::raw('total_amount + commission_amount as amount'), 'paid_amount', 'item_name as description', DB::raw('"purchase" as type'));
            $payments = $laga->payments()
                ->select('id', 'payment_date as date', 'amount', 'notes as description', DB::raw('"payment" as type'));
                
            $advances = LagaAdvance::where('laga_id', $laga->id)
                ->select('id', 'advance_date as date', 'amount', DB::raw('0 as paid_amount'), 'notes as description', DB::raw('"advance" as type'));
                
            if ($request->filled('start_date')) {
                $purchases->where('purchase_date', '>=', $request->start_date);
                $payments->where('payment_date', '>=', $request->start_date);
                $advances->where('advance_date', '>=', $request->start_date);
            }
            if ($request->filled('end_date')) {
                $purchases->where('purchase_date', '<=', $request->end_date);
                $payments->where('payment_date', '<=', $request->end_date);
                $advances->where('advance_date', '<=', $request->end_date);
            }
            
            $rawPurchases = $purchases->get();
            $rawPayments = $payments->get();
            $rawAdvances = $advances->get();
            
            // Sort by date ASC for ledger
            $transactions = $rawPurchases
                ->concat($rawPayments)
                ->concat($rawAdvances)
                ->sortBy(function($t) {
                    $typeOrder = $t->type === 'purchase' ? '0' : ($t->type === 'advance' ? '1' : '2');
                    return $t->date . $typeOrder;
                });
            
            $runningBalance = $openingBalance;
            foreach ($transactions as $t) {
                if ($t->type == 'purchase') {
                    $t->debit = $t->amount;
                    $t->credit = $t->paid_amount;
                    $runningBalance += ($t->debit - $t->credit);
                } elseif ($t->type == 'advance') {
                    $t->debit = $t->amount;
                    $t->credit = 0;
                    $runningBalance += $t->debit;
                } else {
                    $t->debit = 0;
                    $t->credit = $t->amount;
                    $runningBalance -= $t->credit;
                }
                $t->running_balance = $runningBalance;
            }
            $closingBalance = $runningBalance;

            // Ledger Export
            if ($request->input('export') == 'ledger') {
                return response()->streamDownload(function() use ($laga, $transactions, $openingBalance, $closingBalance, $request) {
                    $file = fopen('php://output', 'w');
                    fputs($file, "\xEF\xBB\xBF");
                    
                    // Header Info
                    fputcsv($file, ['خریدار لیجر رپورٹ']);
                    fputcsv($file, ['نام', $laga->name]);
                    fputcsv($file, ['کوڈ', $laga->code]);
                    fputcsv($file, ['تاریخ کی حد', ($request->start_date ?? 'شروع') . ' تا ' . ($request->end_date ?? 'اب تک')]);
                    fputcsv($file, []);
                    
                    fputcsv($file, ['تاریخ', 'قسم', 'تفصیل', 'ڈیبٹ', 'کریڈٹ', 'بیلنس']);
                    fputcsv($file, ['', 'ابتدائی بیلنس', '', '', '', number_format($openingBalance, 2)]);
                    
                    foreach ($transactions as $t) {
                        fputcsv($file, [
                            Carbon::parse($t->date)->format('Y-m-d'),
                            $t->type === 'purchase' ? 'خریداری' : 'ادائیگی',
                            $t->description,
                            number_format($t->debit, 2),
                            number_format($t->credit, 2),
                            number_format($t->running_balance, 2)
                        ]);
                    }
                    
                    fputcsv($file, ['', 'اختتامی بیلنس', '', '', '', number_format($closingBalance, 2)]);
                    fclose($file);
                }, 'ledger_' . $laga->code . '_' . date('Y-m-d') . '.csv');
            }
        }

        if ($request->ajax() || $request->input('ajax')) {
            return view('partials.rokad-table-rows', compact('lagas'));
        }

        return view('rokad', compact('purchasers', 'lagas', 'transactions', 'allPurchaserNames', 'openingBalance', 'closingBalance'));
    }

    public function balanceApi(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string',
            'laga_id' => 'nullable|integer',
        ]);

        if (!$request->filled('code') && !$request->filled('laga_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Missing purchaser identifier.',
            ], 422);
        }

        $query = Laga::query();
        if ($request->filled('laga_id')) {
            $query->where('id', $request->laga_id);
        } else {
            $query->where('code', $request->code);
        }

        $laga = $query->first();

        if (!$laga) {
            return response()->json([
                'success' => false,
                'message' => 'Purchaser not found.',
            ], 404);
        }

        $purchasesQuery = $laga->purchases();
        $paymentsQuery = $laga->payments();
        $advancesQuery = $laga->advances();

        $totalPurchaseAmount = (float) $purchasesQuery->sum(DB::raw('total_amount + commission_amount'));
        $totalInstantPaid = (float) $purchasesQuery->sum('paid_amount');
        $totalLaterPaid = (float) $paymentsQuery->sum('amount');
        $totalAdvance = (float) $advancesQuery->sum('amount');

        $originalMadi = $totalPurchaseAmount + $totalAdvance;
        $totalPaid = $totalInstantPaid + $totalLaterPaid;
        $interest = 0.0;
        $penalties = 0.0;
        $remainingBalance = $originalMadi - $totalPaid + $interest + $penalties;

        return response()->json([
            'success' => true,
            'purchaser' => [
                'id' => $laga->id,
                'code' => $laga->code,
                'name' => $laga->name,
            ],
            'dues' => [
                'original_madi' => $originalMadi,
                'total_paid' => $totalPaid,
                'interest' => $interest,
                'penalties' => $penalties,
                'remaining_balance' => $remainingBalance,
            ],
            'currency' => 'PKR',
        ]);
    }

    public function storeAdvance(Request $request)
    {
        $request->validate([
            'purchaser_id' => 'required|exists:lagas,id',
            'amount' => 'required|numeric|min:0',
            'advance_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['purchaser_id', 'amount', 'advance_date', 'notes']);
        $data['laga_id'] = $data['purchaser_id'];
        unset($data['purchaser_id']);

        LagaAdvance::create($data);

        return redirect()->back()->with('success', 'ایڈوانس رقم کامیابی سے محفوظ ہو گئی ہے۔');
    }

    public function storePayment(Request $request)
    {
        $request->validate([
            'purchaser_id' => 'required|exists:lagas,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['purchaser_id', 'amount', 'payment_date', 'payment_method', 'notes']);
        $table = (new LagaPayment)->getTable();
        if (Schema::hasColumn($table, 'laga_id')) {
            $data['laga_id'] = $data['purchaser_id'];
            unset($data['purchaser_id']);
        } else {
            $data['purchaser_id'] = $data['purchaser_id'];
        }
        LagaPayment::create($data);

        return redirect()->back()->with('success', 'ادائیگی کامیابی سے محفوظ ہو گئی۔');
    }
}
