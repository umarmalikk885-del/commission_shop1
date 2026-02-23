<?php

namespace App\Http\Controllers;

use App\Models\Laga;
use App\Models\Purchase;
use Illuminate\Http\Request;

class PurchaserDetailController extends Controller
{
    /**
     * Display a listing of purchasers.
     */
    public function index(Request $request)
    {
        $query = Laga::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
        }

        $lagas = $query->orderBy('name')->get();

        return view('purchaser_details.index', compact('lagas'));
    }

    /**
     * Display specified purchaser details.
     */
    public function show($id)
    {
        $laga = Laga::findOrFail($id);
        
        // Fetch purchase history sorted by date
        $purchases = Purchase::where('purchaser_code', $laga->code)
                             ->orWhere('customer_name', $laga->name) // Fallback match by name if code missing
                             ->orderBy('purchase_date', 'desc')
                             ->get();

        // Calculate stats
        $stats = [
            'total_items' => $purchases->sum('quantity'),
            'total_amount' => $purchases->sum('total_amount'),
            'total_paid' => $purchases->sum('paid_amount'),
            'balance' => $purchases->sum('total_amount') - $purchases->sum('paid_amount'),
        ];

        return view('purchaser_details.show', compact('laga', 'purchases', 'stats'));
    }
}
