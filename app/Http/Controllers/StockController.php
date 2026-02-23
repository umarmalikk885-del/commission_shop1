<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $stocks = Stock::orderBy('item_name')->get();
        
        // Get low stock items for alerts (exclude items with quantity = 0, those are out of stock)
        $lowStockItems = Stock::whereRaw('quantity <= min_stock_level')
            ->where('quantity', '>', 0)
            ->orderBy('item_name')
            ->get();
        
        $outOfStockItems = Stock::where('quantity', '<=', 0)
            ->orderBy('item_name')
            ->get();

        return view('stock', compact('stocks', 'lowStockItems', 'outOfStockItems'));
    }

    public function store(Request $request)
    {
        // Check if user has permission to manage stock
        $user = auth()->user();
        if ($user && $user->hasRole('User') && !$user->hasPermissionTo('manage stock')) {
            return redirect('/stock')->with('error', 'You do not have permission to create stock items. You can only view stock.');
        }
        $data = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'min_stock_level' => ['required', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $stock = Stock::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock item added successfully.',
                'stock' => $stock
            ]);
        }

        return redirect('/stock')->with('success', 'Stock item added successfully.');
    }

    public function update(Request $request, $id)
    {
        // Check if user has permission to manage stock
        $user = auth()->user();
        if ($user && $user->hasRole('User') && !$user->hasPermissionTo('manage stock')) {
            return redirect('/stock')->with('error', 'You do not have permission to update stock items. You can only view stock.');
        }
        $stock = Stock::findOrFail($id);
        
        $data = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit' => ['nullable', 'string', 'max:50'],
            'min_stock_level' => ['required', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
        ]);

        $stock->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully.',
                'stock' => $stock
            ]);
        }

        return redirect('/stock')->with('success', 'Stock updated successfully.');
    }

    public function destroy($id)
    {
        // Check if user has permission to manage stock
        $user = auth()->user();
        if ($user && $user->hasRole('User') && !$user->hasPermissionTo('manage stock')) {
            return redirect('/stock')->with('error', 'You do not have permission to delete stock items. You can only view stock.');
        }
        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect('/stock')->with('success', 'Stock item deleted successfully.');
    }

    /**
     * Check for stock updates (for real-time synchronization)
     */
    public function updates()
    {
        // Get current stock items with their timestamps
        $stocks = Stock::orderBy('updated_at', 'desc')->get(['id', 'updated_at']);
        
        // Return the latest update timestamp
        $latestUpdate = $stocks->first() ? $stocks->first()->updated_at->timestamp : 0;
        
        return response()->json([
            'updated' => true,
            'latest_update' => $latestUpdate,
            'stocks' => $stocks
        ]);
    }
}
