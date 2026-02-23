<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Laga;
use App\Models\LagaPayment;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RecoveryController extends Controller
{
    public function index(Request $request)
    {
        // Get all lagas with their current balance
        $lagas = Laga::all()->map(function($p) {
            $totalDues = $p->purchases()->sum(DB::raw('total_amount + commission_amount'));
            $totalPayments = $p->payments()->sum('amount') + $p->purchases()->sum('paid_amount');
            $p->balance = $totalDues - $totalPayments;
            return $p;
        })->filter(function($p) {
            return $p->balance > 0;
        })->sortByDesc('balance');

        // Recent recoveries (last 50)
        $recentRecoveries = LagaPayment::with('laga')
            ->orderBy('payment_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        return view('recovery.index', compact('lagas', 'recentRecoveries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'laga_id' => 'required|exists:lagas,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $table = (new LagaPayment)->getTable();
        if (!Schema::hasColumn($table, 'laga_id') && Schema::hasColumn($table, 'purchaser_id')) {
            $data['purchaser_id'] = $data['laga_id'];
            unset($data['laga_id']);
        }

        LagaPayment::create($data);

        return redirect()->route('recovery')->with('success', 'وصولی ریکارڈ کر لی گئی ہے۔');
    }
}
