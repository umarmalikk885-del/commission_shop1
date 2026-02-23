<?php

namespace App\Http\Controllers\Concerns;

use App\Models\BankCashTransaction;

trait CalculatesBankCashBalance
{
    /**
     * Calculate bank and cash balances.
     * Optimized: Single query per account type using conditional aggregation.
     *
     * @return array ['bank' => float, 'cash' => float]
     */
    protected function calculateBalances(): array
    {
        // Optimized: Calculate bank balance in a single query
        $bankBalance = (float) (BankCashTransaction::where('type', 'bank')
            ->selectRaw('
                SUM(CASE 
                    WHEN transaction_type IN ("deposit", "transfer") THEN amount 
                    WHEN transaction_type = "withdrawal" THEN -amount 
                    ELSE 0 
                END) as balance
            ')
            ->value('balance') ?? 0);

        // Optimized: Calculate cash balance in a single query
        $cashBalance = (float) (BankCashTransaction::where('type', 'cash')
            ->selectRaw('
                SUM(CASE 
                    WHEN transaction_type IN ("deposit", "transfer") THEN amount 
                    WHEN transaction_type = "withdrawal" THEN -amount 
                    ELSE 0 
                END) as balance
            ')
            ->value('balance') ?? 0);

        return [
            'bank' => $bankBalance,
            'cash' => $cashBalance,
        ];
    }
}
