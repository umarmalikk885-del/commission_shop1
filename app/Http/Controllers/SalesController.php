<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\BankCashTransaction;
use App\Services\BillNumberService;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    protected $billNumberService;
    protected $metricsService;

    public function __construct(BillNumberService $billNumberService, DashboardMetricsService $metricsService)
    {
        $this->billNumberService = $billNumberService;
        $this->metricsService = $metricsService;
    }

    /**
     * Show sales register (invoices list) for the commission shop.
     */
    public function index(Request $request)
    {
        // Get current authenticated user
        $user = auth()->user();
        
        // Filter invoices by user
        $invoiceQuery = Invoice::with('items')->withCount('items');
        if ($user) {
            $invoiceQuery->where('user_id', $user->id);
        }
        
        $invoices = $invoiceQuery->latest('invoice_date')
            ->latest('id')
            ->paginate(20);

        if ($request->ajax() || $request->wantsJson()) {
            $data = $invoices->map(function (Invoice $invoice) {
                return [
                    'id' => $invoice->id,
                    'invoice_date' => $invoice->invoice_date ? $invoice->invoice_date->translatedFormat('D, d/m/Y') : '—',
                    'bill_no' => $invoice->bill_no,
                    'customer' => $invoice->customer,
                    'items_count' => $invoice->items_count ?? $invoice->items->count(),
                    'total_amount' => number_format((float) $invoice->total_amount, 2),
                ];
            });

            return response()->json([
                'data' => $data,
                'current_page' => $invoices->currentPage(),
                'last_page' => $invoices->lastPage(),
                'total' => $invoices->total(),
            ]);
        }

        // Calculate sales totals (filtered by user)
        // Handle null values by using ?? operator and ensure float conversion
        $baseQuery = Invoice::query();
        if ($user) {
            $baseQuery->where('user_id', $user->id);
        }
        
        $todaySales = (float) ((clone $baseQuery)->whereDate('invoice_date', today())->sum('total_amount') ?? 0);
        $weekSales = (float) ((clone $baseQuery)->where('invoice_date', '>=', now()->subDays(7))->sum('total_amount') ?? 0);
        $monthlySales = (float) ((clone $baseQuery)->where('invoice_date', '>=', now()->startOfMonth())->sum('total_amount') ?? 0);
        $totalSales = (float) ((clone $baseQuery)->sum('total_amount') ?? 0);

        // Calculate bill counts (filtered by user)
        $todayBills = (clone $baseQuery)->whereDate('invoice_date', today())->count();
        $weekBills = (clone $baseQuery)->where('invoice_date', '>=', now()->subDays(7))->count();
        $monthlyBills = (clone $baseQuery)->where('invoice_date', '>=', now()->startOfMonth())->count();
        $totalBills = (clone $baseQuery)->count();

        // Generate next bill number for display in the Add Sale form
        $nextBillNo = $this->billNumberService->generateInvoiceBillNo();

        // Get items for the Add Sale form (item selection)
        $items = \App\Models\Item::all();

        return view('sales', compact(
            'invoices', 
            'todaySales', 
            'weekSales', 
            'monthlySales', 
            'totalSales',
            'todayBills',
            'weekBills',
            'monthlyBills',
            'totalBills',
            'nextBillNo',
            'items'
        ));
    }

    /**
     * Store a new sale (invoice) with items.
     */
    public function store(Request $request)
    {
        // Allow Users to create sales if they have view sales permission
        $user = auth()->user();
        if ($user && $user->hasRole('User')) {
            // Users can create sales if they have view sales permission
            if (!$user->hasPermissionTo('view sales') && !$user->hasPermissionTo('manage sales')) {
                return redirect('/sales')->with('error', 'You do not have permission to create sales.');
            }
        }
        $data = $request->validate([
            'invoice_date' => ['required', 'date'],
            'customer' => ['required', 'string', 'max:255'],
            'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.qty' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.quantity' => ['nullable', 'string'], // Keep for backward compatibility
        ]);

        // If invoice_id is present, we are updating an existing sale
        $invoiceId = $data['invoice_id'] ?? null;
        $invoice = null;
        if ($invoiceId) {
            $invoice = Invoice::findOrFail($invoiceId);
            $billNo = $invoice->bill_no; // Keep existing bill number
        } else {
            // Generate automatic bill number for new sale
            $billNo = $this->billNumberService->generateInvoiceBillNo();
            
            // Ensure uniqueness (in case of race condition or non-sequential numbers)
            $attempts = 0;
            while (Invoice::where('bill_no', $billNo)->exists() && $attempts < 10) {
                // Extract current number and increment
                if (preg_match('/INV-(\d+)/i', $billNo, $matches)) {
                    $nextNumber = (int)$matches[1] + 1;
                    $billNo = 'INV-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                } else {
                    // Fallback: use count + 1
                    $billNo = 'INV-' . str_pad(Invoice::count() + 1, 3, '0', STR_PAD_LEFT);
                }
                $attempts++;
            }
        }

        // Calculate total amount from items
        $totalAmount = 0;
        foreach ($data['items'] as $item) {
            $qty = (float) $item['qty'];
            $rate = (float) $item['rate'];
            $unit = $item['unit'] ?? '';
            
            // Handle dozen conversion: 1 dozen = 12 pieces
            if (strtolower($unit) === 'dozen') {
                $qty = $qty * 12; // Convert dozen to pieces
            }
            
            $itemAmount = $qty * $rate;
            $totalAmount += $itemAmount;
        }
        $totalAmount = round($totalAmount, 2);

        // Get current authenticated user
        $user = auth()->user();
        
        // Use transaction for data integrity
        DB::transaction(function () use (&$invoice, $data, $totalAmount, $billNo, $user) {
            if ($invoice) {
                // Update existing invoice
                $invoice->update([
                    'invoice_date' => $data['invoice_date'],
                    'customer' => $data['customer'],
                    'total_amount' => $totalAmount,
                ]);

                // Remove old items
                $invoice->items()->delete();
            } else {
                // Create new invoice with user_id
                $invoice = Invoice::create([
                    'bill_no' => $billNo,
                    'invoice_date' => $data['invoice_date'],
                    'customer' => $data['customer'],
                    'total_amount' => $totalAmount,
                    'user_id' => $user ? $user->id : null,
                ]);
            }

            // Create invoice items (for both new and updated invoices) - Optimized with bulk insert
            $itemsData = [];
            foreach ($data['items'] as $item) {
                $qty = (float) $item['qty'];
                $rate = (float) $item['rate'];
                $unit = $item['unit'] ?? '';
                
                // Handle dozen conversion: 1 dozen = 12 pieces
                if (strtolower($unit) === 'dozen') {
                    $qty = $qty * 12; // Convert dozen to pieces for calculation
                }
                
                $itemAmount = round($qty * $rate, 2);
                
                // Format quantity string for display (e.g., "1 Dozen" or "2.5 kg")
                $quantityDisplay = $item['qty'] . ($unit ? ' ' . ucfirst($unit) : '');
                
                $itemsData[] = [
                    'invoice_id' => $invoice->id,
                    'item_name' => $item['item_name'],
                    'quantity' => $quantityDisplay, // Display format
                    'qty' => (float) $item['qty'], // Numeric quantity
                    'unit' => $unit,
                    'rate' => $rate,
                    'amount' => $itemAmount,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($itemsData)) {
                InvoiceItem::insert($itemsData);
            }
            
            // Auto-create transaction for sales (deposit)
            $existingTransaction = BankCashTransaction::where('invoice_id', $invoice->id)->first();
            
            if ($existingTransaction) {
                // Update existing transaction
                $existingTransaction->update([
                    'transaction_date' => $invoice->invoice_date,
                    'amount' => $totalAmount,
                    'description' => 'Sales deposit - ' . $invoice->bill_no,
                    'notes' => 'Auto-generated from invoice ' . $invoice->bill_no,
                ]);
            } else {
                // Create new transaction
                BankCashTransaction::create([
                    'transaction_date' => $invoice->invoice_date,
                    'type' => 'cash', // Default to cash for sales
                    'transaction_type' => 'deposit',
                    'amount' => $totalAmount,
                    'description' => 'Sales deposit - ' . $invoice->bill_no,
                    'notes' => 'Auto-generated from invoice ' . $invoice->bill_no,
                    'invoice_id' => $invoice->id,
                ]);
            }
        });

        // Clear dashboard cache when invoice is created/updated
        $this->metricsService->clearCache($user);

        $message = $invoiceId ? 'Sale updated successfully.' : 'Sale recorded successfully.';
        return redirect('/sales')->with('success', $message);
    }

    /**
     * Return invoice + items as JSON for editing in the UI
     */
    public function showJson($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return response()->json($invoice);
    }

    /**
     * Show invoice for printing (A4 format)
     */
    public function print($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        
        // Get latest company settings if available
        $company = \App\Models\CompanySetting::current();
        
        return view('invoice-print', compact('invoice', 'company'));
    }

    /**
     * Delete a sale (invoice) and its items.
     */
    public function destroy($id)
    {
        // Allow Users to delete their own sales if they have view sales permission
        $user = auth()->user();
        
        // Fetch invoice once with items and user check
        $invoice = Invoice::with('items')->findOrFail($id);
        
        if ($user && $user->hasRole('User')) {
            // Check if the invoice belongs to the user
            if ($invoice->user_id != $user->id) {
                return redirect('/sales')->with('error', 'You can only delete your own sales.');
            }
            // Users can delete their own sales if they have view sales permission
            if (!$user->hasPermissionTo('view sales') && !$user->hasPermissionTo('manage sales')) {
                return redirect('/sales')->with('error', 'You do not have permission to delete sales.');
            }
        }

        // Delete related items first, then the invoice
        $invoice->items()->delete();
        $invoice->delete();

        // Clear dashboard cache when invoice is deleted
        $this->metricsService->clearCache($user);

        return redirect('/sales')->with('success', 'Sale deleted successfully.');
    }
}
