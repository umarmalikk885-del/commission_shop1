<?php

namespace App\Http\Controllers\Concerns;

trait HandlesPeriodFilter
{
    /**
     * Apply period filter to a query builder.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $period
     * @param string $dateColumn
     * @return void
     */
    protected function applyPeriodFilter($query, string $period, string $dateColumn = 'created_at'): void
    {
        switch ($period) {
            case 'weekly':
                $query->where($dateColumn, '>=', now()->subDays(7));
                break;
            case 'monthly':
                $query->where($dateColumn, '>=', now()->startOfMonth());
                break;
            case 'three_months':
                $query->where($dateColumn, '>=', now()->subMonths(3)->startOfMonth());
                break;
            case 'yearly':
                $query->where($dateColumn, '>=', now()->startOfYear());
                break;
            case 'all':
            default:
                // No filter needed
                break;
        }
    }
}
