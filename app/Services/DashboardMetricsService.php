<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Purchase;
use App\Models\Vendor;
use App\Models\Stock;
use Illuminate\Support\Facades\Cache;

class DashboardMetricsService
{
    /**
     * Get sales metrics for a user
     * 
     * @param \App\Models\User|null $user
     * @return array
     */
    public function getSalesMetrics($user = null): array
    {
        $cacheKey = 'dashboard_sales_metrics_' . ($user ? $user->id : 'all');
        
        return Cache::remember($cacheKey, 300, function () use ($user) {
            $query = Invoice::query();
            if ($user) {
                $query->where('user_id', $user->id);
            }
            
            return [
                'today' => (float) ((clone $query)->whereDate('invoice_date', today())->sum('total_amount') ?? 0),
                'week' => (float) ((clone $query)->where('invoice_date', '>=', now()->subDays(7))->sum('total_amount') ?? 0),
                'month' => (float) ((clone $query)->where('invoice_date', '>=', now()->startOfMonth())->sum('total_amount') ?? 0),
                'year' => (float) ((clone $query)->where('invoice_date', '>=', now()->startOfYear())->sum('total_amount') ?? 0),
                'total' => (float) ((clone $query)->sum('total_amount') ?? 0),
            ];
        });
    }

    /**
     * Get purchase dues metrics
     * 
     * @param \App\Models\User|null $user
     * @return float
     */
    public function getPurchaseDues($user = null): float
    {
        $cacheKey = 'dashboard_purchase_dues_' . ($user ? $user->id : 'all');
        
        return Cache::remember($cacheKey, 300, function () use ($user) {
            $query = Purchase::whereRaw('total_amount > COALESCE(paid_amount, 0)');
            if ($user) {
                $query->where('user_id', $user->id);
            }
            
            return (float) ($query->selectRaw('SUM(total_amount - COALESCE(paid_amount, 0)) as total')
                ->value('total') ?? 0);
        });
    }

    /**
     * Get vendor metrics
     * 
     * @return array
     */
    public function getVendorMetrics(): array
    {
        return Cache::remember('dashboard_vendor_metrics', 600, function () {
            return [
                'total' => Vendor::count(),
                'active' => Vendor::where('status', 'active')->count(),
            ];
        });
    }

    /**
     * Get stock alerts
     * 
     * @return array
     */
    public function getStockAlerts(): array
    {
        return Cache::remember('dashboard_stock_alerts', 300, function () {
            return [
                'low' => Stock::whereRaw('quantity <= min_stock_level')
                    ->where('quantity', '>', 0)
                    ->orderBy('item_name')
                    ->get(),
                'out' => Stock::where('quantity', '<=', 0)
                    ->orderBy('item_name')
                    ->get(),
            ];
        });
    }

    /**
     * Clear dashboard cache for a user
     * 
     * @param \App\Models\User|null $user
     * @return void
     */
    public function clearCache($user = null): void
    {
        $userId = $user ? $user->id : 'all';
        Cache::forget("dashboard_sales_metrics_{$userId}");
        Cache::forget("dashboard_purchase_dues_{$userId}");
        Cache::forget('dashboard_vendor_metrics');
        Cache::forget('dashboard_stock_alerts');
    }
}
