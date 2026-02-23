<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\BankCashTransaction;
use App\Models\InvoiceItem;
use App\Services\DashboardMetricsService;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DashboardController extends Controller
{
    protected $metricsService;

    public function __construct(DashboardMetricsService $metricsService)
    {
        $this->metricsService = $metricsService;
    }

    public function index()
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Determine current application language for layout (LTR/RTL)
        $appLanguage = app()->getLocale();
        // Normalize to a simple value we can reliably use in Blade
        if ($appLanguage === null) {
            $appLanguage = 'ur';
        } elseif (str_starts_with($appLanguage, 'ur')) {
            // Handle locales like "ur" or "ur_PK"
            $appLanguage = 'ur';
        } else {
            $appLanguage = 'ur';
        }

        // Commission Shop Metrics - Sales (filtered by user) - Optimized with caching
        $salesMetrics = $this->metricsService->getSalesMetrics($user);
        $todaySales = $salesMetrics['today'];
        $weekSales = $salesMetrics['week'];
        $monthlySales = $salesMetrics['month'];
        $yearlySales = $salesMetrics['year'];
        $totalSales = $salesMetrics['total'];
        
        // Total Dues (Purchase Dues) - Optimized with caching
        $totalPurchaseDues = $this->metricsService->getPurchaseDues($user);

        // Vendor Metrics - Optimized with caching
        $vendorMetrics = $this->metricsService->getVendorMetrics();
        $totalVendors = $vendorMetrics['total'];
        $activeVendors = $vendorMetrics['active'];

        // Total Purchases Today (filtered by user)
        $purchaseBaseQuery = Purchase::query();
        if ($user) {
            $purchaseBaseQuery->where('user_id', $user->id);
        }
        $todayPurchases = (float) ((clone $purchaseBaseQuery)->whereDate('purchase_date', today())->sum('total_amount') ?? 0);

        // Stock Alerts - Optimized with caching
        $stockAlerts = $this->metricsService->getStockAlerts();
        $lowStockItems = $stockAlerts['low'];
        $outOfStockItems = $stockAlerts['out'];

        // Searchable data for global search - Lazy loaded via AJAX to improve page load performance
        // These are now loaded on-demand when search is used, reducing initial page load time
        $searchableVendors = collect();
        $searchablePurchases = collect();
        $searchableInvoices = collect();
        $searchableStocks = collect();
        $searchableTransactions = collect();
        $searchableBankCash = collect();
        $searchableInvoiceItems = collect();
        
        // Calculate total dues
        $totalDues = (float) ($totalPurchaseDues);
        
        return view('dashboard', compact(
            'appLanguage',
            'todaySales',
            'weekSales',
            'monthlySales',
            'yearlySales',
            'totalSales',
            'totalPurchaseDues',
            'totalDues',
            'totalVendors',
            'activeVendors',
            'todayPurchases',
            'lowStockItems',
            'outOfStockItems',
            'searchableVendors',
            'searchablePurchases',
            'searchableInvoices',
            'searchableStocks',
            'searchableTransactions',
            'searchableBankCash',
            'searchableInvoiceItems'
        ));
    }
}
