<?php

namespace App\Http\Controllers;

use App\Models\BakriBook;
use App\Models\Purchase;
use App\Models\Invoice;
use App\Models\Vendor;
use App\Models\BankCashTransaction;
use App\Models\Laga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Determine current application language for layout (LTR/RTL)
        $appLanguage = app()->getLocale();
        if ($appLanguage === null) {
            $appLanguage = 'ur';
        } elseif (str_starts_with($appLanguage, 'ur')) {
            $appLanguage = 'ur';
        } else {
            $appLanguage = 'ur';
        }

        // Initialize variables
        $dateResults = null;
        $dateTotals = null;
        $searchResults = null;
        $searchTotals = null;
        $farmerResults = null;
        $farmerTotals = null;
        $lagaResults = null;
        $lagaTotals = null;

        // Get all vendors (farmers) for dropdown
        $farmers = Vendor::orderBy('name')->get();

        // ============ DATE RANGE SEARCH (BakriBook) ============
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        
        if ($fromDate && $toDate) {
            $dateResults = BakriBook::whereBetween('record_date', [$fromDate, $toDate])
                ->orderBy('record_date', 'desc')
                ->get();
            
            $dateTotals = [
                'count' => $dateResults->count(),
                'raw_goat' => $dateResults->sum('raw_goat'),
                'total_expenses' => $dateResults->sum('total_expenses'),
                'net_goat' => $dateResults->sum('net_goat'),
                'commission' => $dateResults->sum('commission'),
            ];

            if ($request->input('export') === 'bakri_date_csv') {
                return $this->exportBakriBookCsv($dateResults);
            }
        }

        // ============ BAKRI BOOK SEARCH ============
        $searchQuery = $request->input('search_query');
        $searchType = $request->input('search_type', 'all');
        
        if ($searchQuery) {
            $query = BakriBook::query();
            
            if ($searchType === 'goat_number') {
                $query->where('goat_number', 'like', '%' . $searchQuery . '%');
            } elseif ($searchType === 'trader') {
                $query->where('trader', 'like', '%' . $searchQuery . '%');
            } else {
                $query->where(function($q) use ($searchQuery) {
                    $q->where('goat_number', 'like', '%' . $searchQuery . '%')
                      ->orWhere('trader', 'like', '%' . $searchQuery . '%');
                });
            }
            
            $searchResults = $query->orderBy('record_date', 'desc')->get();
            
            $searchTotals = [
                'count' => $searchResults->count(),
                'raw_goat' => $searchResults->sum('raw_goat'),
                'total_expenses' => $searchResults->sum('total_expenses'),
                'net_goat' => $searchResults->sum('net_goat'),
                'commission' => $searchResults->sum('commission'),
                'unique_traders' => $searchResults->pluck('trader')->unique()->count(),
            ];

            if ($request->input('export') === 'bakri_search_csv') {
                return $this->exportBakriBookCsv($searchResults);
            }
        }

        // ============ LAGA SEARCH (from Purchases) ============
        $lagaQuery = $request->input('laga_query') ?? $request->input('purchaser_query');
        $lagaCode = $request->input('laga_code') ?? $request->input('purchaser_code');
        $itemName = $request->input('item_name');
        $lagaFromDate = $request->input('laga_from_date') ?? $request->input('purchaser_from_date');
        $lagaToDate = $request->input('laga_to_date') ?? $request->input('purchaser_to_date');
        $paymentStatus = $request->input('payment_status');
        $minTotal = $request->input('min_total');
        $maxTotal = $request->input('max_total');
        $perPage = $request->input('per_page', 10);
        
        if ($lagaQuery || $lagaCode || $itemName || ($lagaFromDate && $lagaToDate) || $paymentStatus || $minTotal !== null || $maxTotal !== null || $request->has('laga_search') || $request->has('purchaser_search')) {
            $query = Purchase::with('vendor');
            
            if ($lagaQuery) {
                $query->where('customer_name', 'like', '%' . $lagaQuery . '%');
            }

            if ($lagaCode) {
                $query->where('purchaser_code', 'like', '%' . $lagaCode . '%');
            }

            if ($itemName) {
                $query->where('item_name', 'like', '%' . $itemName . '%');
            }
            
            if ($lagaFromDate && $lagaToDate) {
                $query->whereBetween('purchase_date', [$lagaFromDate, $lagaToDate]);
            }

            if ($paymentStatus === 'paid') {
                $query->whereColumn('paid_amount', '>=', 'total_amount');
            } elseif ($paymentStatus === 'partial') {
                $query->whereNotNull('paid_amount')
                    ->where('paid_amount', '>', 0)
                    ->whereColumn('paid_amount', '<', 'total_amount');
            } elseif ($paymentStatus === 'unpaid') {
                $query->where(function ($q) {
                    $q->whereNull('paid_amount')
                      ->orWhere('paid_amount', 0);
                });
            }

            if ($minTotal !== null && $minTotal !== '') {
                $query->where('total_amount', '>=', (float)$minTotal);
            }

            if ($maxTotal !== null && $maxTotal !== '') {
                $query->where('total_amount', '<=', (float)$maxTotal);
            }

            // Export Logic
            if ($request->input('export') === 'laga_csv' || $request->input('export') === 'purchaser_csv') {
                return $this->exportLagaCsv($query->orderBy('purchase_date', 'desc')->get());
            }
            
            // Calculate totals before pagination
            $totalsQuery = clone $query;
            $lagaTotals = [
                'count' => $totalsQuery->count(),
                'items_sold' => $totalsQuery->sum('quantity'),
                'total_amount' => $totalsQuery->sum('total_amount'),
                'commission' => $totalsQuery->sum('commission_amount'),
                'paid_amount' => $totalsQuery->sum('paid_amount'),
                'batta' => $totalsQuery->sum(DB::raw('total_amount - COALESCE(paid_amount, 0)')),
                'unique_lagas' => $totalsQuery->distinct('customer_name')->count('customer_name'),
            ];

            $lagaResults = $query->orderBy('purchase_date', 'desc')
                ->paginate($perPage)
                ->withQueryString();

            if ($request->ajax()) {
                return view('partials.purchaser-report-results', compact('lagaResults', 'lagaTotals'))->render();
            }
        }

        // ============ FARMER SEARCH (from Purchases via Vendor) ============
        $farmerId = $request->input('farmer_id');
        $farmerFromDate = $request->input('farmer_from_date');
        $farmerToDate = $request->input('farmer_to_date');
        
        if ($farmerId || ($farmerFromDate && $farmerToDate)) {
            $query = Purchase::with('vendor');
            
            if ($farmerId) {
                $query->where('vendor_id', $farmerId);
            }
            
            if ($farmerFromDate && $farmerToDate) {
                $query->whereBetween('purchase_date', [$farmerFromDate, $farmerToDate]);
            }
            
            $farmerResults = $query->orderBy('purchase_date', 'desc')->get();
            
            if ($request->input('export') === 'farmer_csv') {
                return $this->exportFarmerCsv($farmerResults);
            }

            $farmerTotals = [
                'count' => $farmerResults->count(),
                'items_sold' => $farmerResults->sum('quantity'),
                'total_amount' => $farmerResults->sum('total_amount'),
                'commission' => $farmerResults->sum('commission_amount'),
                'batta' => $farmerResults->sum(function($p) {
                    return $p->total_amount - ($p->paid_amount ?? 0);
                }),
                'farmer_name' => $farmerId ? (Vendor::find($farmerId)->name ?? 'نامعلوم') : null,
            ];
        }

        // ============ CODE SEARCH (Unique ID) ============
        $searchCode = $request->input('search_code');
        $codeResult = null;
        $codeType = null;
        $codeTransactions = null;

        if ($searchCode) {
            $searchCode = trim($searchCode);
            // Farmer Code (5 digits)
            if (strlen($searchCode) === 5) {
                $vendor = Vendor::where('code', $searchCode)->first();
                if ($vendor) {
                    $codeType = 'farmer';
                    $codeResult = $vendor;
                    
                    // Get Transaction History
                    // 1. Purchases (Items sold by this farmer)
                    $purchases = Purchase::where('vendor_id', $vendor->id)
                        ->orderBy('purchase_date', 'desc')
                        ->paginate(10, ['*'], 'purchases_page')
                        ->withQueryString();
                        
                    $codeTransactions = [
                        'purchases' => $purchases
                    ];
                }
            } 
            // Purchaser Code (6 digits)
            elseif (strlen($searchCode) === 6) {
                $purchaser = Laga::where('code', $searchCode)->first();
                if ($purchaser) {
                    $codeType = 'purchaser';
                    $codeResult = $purchaser;
                    
                    // Get Transaction History
                    // Purchases (Items bought by this purchaser)
                    $purchases = Purchase::where('purchaser_code', $searchCode)
                        ->orderBy('purchase_date', 'desc')
                        ->paginate(10, ['*'], 'purchases_page')
                        ->withQueryString();
                        
                    $codeTransactions = [
                        'purchases' => $purchases
                    ];
                }
            }
        }
        
        return view('reports', compact(
            'appLanguage',
            'dateResults',
            'dateTotals',
            'searchResults',
            'searchTotals',
            'lagaResults',
            'lagaTotals',
            'farmers',
            'farmerResults',
            'farmerTotals',
            'codeResult',
            'codeType',
            'codeTransactions',
            'fromDate',
            'toDate',
            'searchQuery',
            'searchType',
            'lagaQuery',
                'lagaCode',
                'lagaFromDate',
                'lagaToDate',
                'paymentStatus',
                'minTotal',
                'maxTotal',
            'farmerId',
            'farmerFromDate',
            'farmerToDate',
            'searchCode',
            'perPage'
        ));
    }

    private function exportLagaCsv($results)
    {
        $filename = 'laga-report-' . date('Y-m-d-H-i-s') . '.csv';
        
        return response()->streamDownload(function() use ($results) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for Excel UTF-8
            
            fputcsv($file, ['Laga Report', '', '', '', '', '', '', '', '']);
            fputcsv($file, ['Generated on:', date('d/m/Y H:i A'), '', '', '', '', '', '', '']);
            fputcsv($file, ['', '', '', '', '', '', '', '', '']);
            
            // Header
            fputcsv($file, ['Date', 'Bill #', 'Laga Name', 'Code', 'Item', 'Qty', 'Rate', 'Total Amount', 'Paid Amount', 'Balance']);
            
            foreach ($results as $row) {
                $balance = $row->total_amount - ($row->paid_amount ?? 0);
                fputcsv($file, [
                    $row->purchase_date->format('d/m/Y'),
                    $row->bill_number,
                    $row->customer_name,
                    $row->purchaser_code,
                    $row->item_name,
                    $row->quantity,
                    $row->rate,
                    $row->total_amount,
                    $row->paid_amount ?? 0,
                    $balance
                ]);
            }
            
            fclose($file);
        }, $filename);
    }

    private function exportBakriBookCsv($results)
    {
        $filename = 'bakri-book-report-' . date('Y-m-d-H-i-s') . '.csv';
        
        return response()->streamDownload(function() use ($results) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for Excel UTF-8
            
            fputcsv($file, ['Bakri Book Report', '', '', '', '', '', '']);
            fputcsv($file, ['Generated on:', date('d/m/Y H:i A'), '', '', '', '', '']);
            fputcsv($file, ['', '', '', '', '', '', '']);
            
            // Header
            fputcsv($file, ['Date', 'Goat #', 'Trader', 'Raw Goat', 'Expenses', 'Net Goat', 'Commission']);
            
            foreach ($results as $row) {
                fputcsv($file, [
                    $row->record_date->format('d/m/Y'),
                    $row->goat_number,
                    $row->trader,
                    $row->raw_goat,
                    $row->total_expenses,
                    $row->net_goat,
                    $row->commission
                ]);
            }
            
            fclose($file);
        }, $filename);
    }

    private function exportFarmerCsv($results)
    {
        $filename = 'farmer-report-' . date('Y-m-d-H-i-s') . '.csv';
        
        return response()->streamDownload(function() use ($results) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for Excel UTF-8
            
            fputcsv($file, ['Vendor/Farmer Report', '', '', '', '', '', '', '', '']);
            fputcsv($file, ['Generated on:', date('d/m/Y H:i A'), '', '', '', '', '', '', '']);
            fputcsv($file, ['', '', '', '', '', '', '', '', '']);
            
            // Header
            fputcsv($file, ['Date', 'Bill #', 'Laga', 'Item', 'Qty', 'Rate', 'Total Amount', 'Paid', 'Balance']);
            
            foreach ($results as $row) {
                $balance = $row->total_amount - ($row->paid_amount ?? 0);
                fputcsv($file, [
                    $row->purchase_date->format('d/m/Y'),
                    $row->bill_number,
                    $row->customer_name,
                    $row->item_name,
                    $row->quantity,
                    $row->rate,
                    $row->total_amount,
                    $row->paid_amount ?? 0,
                    $balance
                ]);
            }
            
            fclose($file);
        }, $filename);
    }

    public function balanceSheet(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Assets (Income) from BakriBook and Purchase
        // These are earnings for the commission shop collected from traders/farmers
        $incomeQuery = BakriBook::whereBetween('record_date', [$startDate, $endDate]);
        
        // Purchase Commission
        $purchaseCommission = Purchase::whereBetween('purchase_date', [$startDate, $endDate])
            ->sum('commission_amount');

        // Other Deposits (Standalone deposits in Bank/Cash not linked to invoices)
        $otherDeposits = BankCashTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'deposit')
            ->whereNull('purchase_id')
            ->whereNull('invoice_id')
            ->sum('amount');
        
        // Calculate totals for each income category
        // Note: 'stamp' is mapped to Laagaa/Fees based on user request context
        // 'labor' is Mazdore
        // 'mashiana' is Manshiyana
        // 'commission' is Commission
        $incomes = [
            'commission' => $incomeQuery->sum('commission'),
            'purchase_commission' => $purchaseCommission,
            'labor' => $incomeQuery->sum('labor'), // Mazdore
            'mashiana' => $incomeQuery->sum('mashiana'), // Manshiyana
            'laagaa' => $incomeQuery->sum('stamp'), // Using stamp for Laagaa/Fees
            'other_deposits' => $otherDeposits,
            'other' => $incomeQuery->sum('other_expenses'),
        ];
        
        $totalIncome = array_sum($incomes);
        
        // Existing deposits in the selected period for accumulation display
        $existingCommission = BankCashTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'deposit')
            ->where('description', 'کمیشن (بھیڑ/بکری)')
            ->sum('amount');
        $existingLaagaa = BankCashTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'deposit')
            ->where('description', 'لاگا')
            ->sum('amount');
        $existingMashiana = BankCashTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'deposit')
            ->where('description', 'منشیانہ')
            ->sum('amount');
        $existingLabor = BankCashTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'deposit')
            ->where('description', 'مزدوری')
            ->sum('amount');
        
        $autoAmounts = [
            'commission' => ($incomes['commission'] ?? 0) + $existingCommission,
            'laagaa' => ($incomes['laagaa'] ?? 0) + $existingLaagaa,
            'mashiana' => ($incomes['mashiana'] ?? 0) + $existingMashiana,
            'labor' => ($incomes['labor'] ?? 0) + $existingLabor,
        ];

        // Liabilities (Expenses) from BankCashTransaction
        // We focus on standalone withdrawals (no purchase_id) which represent operational expenses
        $baseExpenseQuery = BankCashTransaction::whereBetween('transaction_date', [$startDate, $endDate])
            ->where('transaction_type', 'withdrawal')
            ->whereNull('purchase_id')
            ->whereNull('invoice_id');
        
        $expenseItems = (clone $baseExpenseQuery)
            ->select('description', DB::raw('SUM(amount) as total'))
            ->groupBy('description')
            ->orderBy('total', 'desc')
            ->get();

        $shopRentLabels = ['Rent', 'Shop Rent', 'Monthly Rent', 'کرایہ دکان', 'دکان کرایہ'];
        $foodSalaryLabels = ['Food Salary', 'Operator Food Salary', 'Employee Food Salary', 'کھانے کی تنخواہ', 'کھانا تنخواہ', 'خوراک'];
        $formerPaidLabels = ['Former Payment', 'Previous Owner Payment', 'Landlord Payment', 'سابقہ مالک ادائیگی', 'مکان مالک ادائیگی', 'کل کسان ایڈوانس', 'کل کسان ایڈوانس رقم'];
        $shopRentLike = ['%Rent%', '%Advance Rent%', '%Rent Advance%', '%کرایہ%', '%پیشگی کرایہ%'];
        $foodSalaryLike = ['%Food%', '%Food Expense%', '%Food Expenses%', '%Food Salary%', '%کھانا%', '%خوراک%', '%تنخواہ%'];
        $formerPaidLike = ['%Former%', '%Previous Owner%', '%Landlord%', '%ادائیگی%', '%سابقہ%', '%مالک%', '%کسان%', '%ایڈوانس%'];

        $sumByLabels = function ($labels, $likes) use ($baseExpenseQuery) {
            $q = (clone $baseExpenseQuery);
            $q->where(function ($sub) use ($labels, $likes) {
                if (!empty($labels)) {
                    $sub->orWhereIn('description', $labels);
                }
                foreach ($likes as $pattern) {
                    $sub->orWhere('description', 'like', $pattern);
                }
            });
            return (float)$q->sum('amount');
        };

        $shopRent = $sumByLabels($shopRentLabels, $shopRentLike);
        $foodSalary = $sumByLabels($foodSalaryLabels, $foodSalaryLike);
        $formerPaid = $sumByLabels($formerPaidLabels, $formerPaidLike);
        
        $labelsFlat = array_merge($shopRentLabels, $foodSalaryLabels, $formerPaidLabels);
        $expenseItems = $expenseItems->filter(function ($item) use ($labelsFlat) {
            return !in_array($item->description, $labelsFlat, true);
        });
        
        $expenseItems->push((object)['description' => 'دکان کرایہ (ماہانہ)', 'total' => $shopRent]);
        $expenseItems->push((object)['description' => 'خوراک', 'total' => $foodSalary]);
        $expenseItems->push((object)['description' => 'کل کسان ایڈوانس رقم', 'total' => $formerPaid]);
        
        $totalExpenses = $expenseItems->sum('total');

        // Net Balance (Profit/Loss)
        $netBalance = $totalIncome - $totalExpenses;

        // Export functionality
        if ($request->has('export')) {
            return $this->exportBalanceSheet($incomes, $expenseItems, $totalIncome, $totalExpenses, $startDate, $endDate);
        }

        return view('balance-sheet', compact(
            'incomes', 
            'autoAmounts',
            'expenseItems', 
            'totalIncome', 
            'totalExpenses', 
            'netBalance',
            'startDate', 
            'endDate'
        ));
    }

    private function exportBalanceSheet($incomes, $expenseItems, $totalIncome, $totalExpenses, $startDate, $endDate)
    {
        $filename = 'balance-sheet-' . $startDate . '-to-' . $endDate . '.csv';
        
        return response()->streamDownload(function() use ($incomes, $expenseItems, $totalIncome, $totalExpenses, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for Excel UTF-8
            
            fputcsv($file, ['Balance Sheet Report', '', '']);
            fputcsv($file, ['Period:', $startDate . ' to ' . $endDate, '']);
            fputcsv($file, ['', '', '']);
            
            // Header
            fputcsv($file, ['Category', 'Description', 'Amount']);
            
            // Assets / Income
            fputcsv($file, ['ASSETS (INCOME)', '', '']);
            fputcsv($file, ['', 'Commission Earnings (Goat/Sheep)', number_format($incomes['commission'], 2)]);
            fputcsv($file, ['', 'Purchase Commission (General)', number_format($incomes['purchase_commission'], 2)]);
            fputcsv($file, ['', 'Laagaa (Advance)', number_format($incomes['laagaa'], 2)]);
            fputcsv($file, ['', 'Manshiyana (Daily Wages)', number_format($incomes['mashiana'], 2)]);
            fputcsv($file, ['', 'Mazdore (Labor Costs)', number_format($incomes['labor'], 2)]);
            fputcsv($file, ['', 'Other Deposits', number_format($incomes['other_deposits'], 2)]);
            fputcsv($file, ['', 'Other Income', number_format($incomes['other'], 2)]);
            fputcsv($file, ['', 'TOTAL ASSETS', number_format($totalIncome, 2)]);
            fputcsv($file, ['', '', '']);
            
            // Liabilities / Expenses
            fputcsv($file, ['LIABILITIES (EXPENSES)', '', '']);
            foreach ($expenseItems as $item) {
                fputcsv($file, ['', $item->description, number_format($item->total, 2)]);
            }
            fputcsv($file, ['', 'TOTAL LIABILITIES', number_format($totalExpenses, 2)]);
            fputcsv($file, ['', '', '']);
            
            // Net
            fputcsv($file, ['NET BALANCE', '', number_format($totalIncome - $totalExpenses, 2)]);
            
            fclose($file);
        }, $filename);
    }

    public function addExpense(Request $request)
    {
        $data = $request->validate([
            'transaction_date' => ['nullable', 'date'],
            'type' => ['nullable', 'in:bank,cash'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $date = $data['transaction_date'] ?? $data['end_date'] ?? now()->toDateString();
        $type = $data['type'] ?? 'cash';

        \App\Models\BankCashTransaction::create([
            'transaction_date' => $date,
            'type' => $type,
            'transaction_type' => 'withdrawal',
            'amount' => $data['amount'],
            'description' => $data['description'],
            'notes' => null,
            'purchase_id' => null,
            'invoice_id' => null,
        ]);

        return redirect()->route('balance-sheet', [
            'start_date' => $data['start_date'] ?? $date,
            'end_date' => $data['end_date'] ?? $date,
        ])->with('success', 'Expense inserted.');
    }

    public function addIncome(Request $request)
    {
        $data = $request->validate([
            'transaction_date' => ['nullable', 'date'],
            'type' => ['nullable', 'in:bank,cash'],
            'description' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ]);

        $date = $data['transaction_date'] ?? $data['end_date'] ?? now()->toDateString();
        $type = $data['type'] ?? 'cash';

        \App\Models\BankCashTransaction::create([
            'transaction_date' => $date,
            'type' => $type,
            'transaction_type' => 'deposit',
            'amount' => $data['amount'],
            'description' => $data['description'],
            'notes' => null,
            'purchase_id' => null,
            'invoice_id' => null,
        ]);

        return redirect()->route('balance-sheet', [
            'start_date' => $data['start_date'] ?? $date,
            'end_date' => $data['end_date'] ?? $date,
        ])->with('success', 'Income inserted.');
    }
}
