<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\HandlesPeriodFilter;
use App\Http\Controllers\Concerns\CalculatesBankCashBalance;
use App\Models\BankCashTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    use HandlesPeriodFilter, CalculatesBankCashBalance;
    /**
     * Show the bank/cash page.
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');
        $query = BankCashTransaction::with(['purchase', 'invoice']);

        $this->applyPeriodFilter($query, $period, 'transaction_date');

        $transactions = $query->latest('transaction_date')
            ->latest('id')
            ->get();

        $balances = $this->calculateBalances();

        return view('bank-cash', [
            'transactions' => $transactions,
            'bankBalance' => $balances['bank'],
            'cashBalance' => $balances['cash'],
            'period' => $period,
        ]);
    }

    /**
     * Store a new bank/cash transaction.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'transaction_date' => ['required', 'date'],
            'type' => ['required', 'in:bank,cash'],
            'transaction_type' => ['required', 'in:deposit,withdrawal,transfer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        BankCashTransaction::create($data);

        return redirect($this->getRedirectUrl($request->get('period')))
            ->with('success', 'Transaction saved successfully.');
    }

    /**
     * Show the bank/cash page with a specific transaction loaded for editing.
     */
    public function edit(Request $request, BankCashTransaction $transaction)
    {
        $period = $request->get('period', 'all');
        $query = BankCashTransaction::with(['purchase', 'invoice']);

        $this->applyPeriodFilter($query, $period, 'transaction_date');

        $transactions = $query->latest('transaction_date')
            ->latest('id')
            ->get();

        $balances = $this->calculateBalances();

        return view('bank-cash', [
            'transactions' => $transactions,
            'bankBalance' => $balances['bank'],
            'cashBalance' => $balances['cash'],
            'period' => $period,
            'editingTransaction' => $transaction,
        ]);
    }

    /**
     * Update an existing transaction.
     */
    public function update(Request $request, BankCashTransaction $transaction)
    {
        $data = $request->validate([
            'transaction_date' => ['required', 'date'],
            'type' => ['required', 'in:bank,cash'],
            'transaction_type' => ['required', 'in:deposit,withdrawal,transfer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $transaction->update($data);

        return redirect($this->getRedirectUrl($request->get('period')))
            ->with('success', 'Transaction updated successfully.');
    }

    /**
     * Delete a transaction.
     */
    public function destroy(Request $request, BankCashTransaction $transaction)
    {
        $transaction->delete();

        return redirect($this->getRedirectUrl($request->get('period')))
            ->with('success', 'Transaction deleted successfully.');
    }

    /**
     * Get redirect URL with optional period parameter.
     *
     * @param string|null $period
     * @return string
     */
    protected function getRedirectUrl(?string $period): string
    {
        $url = '/bank-cash';
        if ($period && $period !== 'all') {
            $url .= '?period=' . $period;
        }
        return $url;
    }
}

