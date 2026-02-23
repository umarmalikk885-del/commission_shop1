<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::orderBy('created_at', 'desc')->get();
        
        if ($request->wantsJson()) {
            return response()->json($items);
        }

        return view('items.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'urdu_name' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:items,code',
            'type' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'rate' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
        ]);

        $item = Item::create([
            'name' => $request->name,
            'urdu_name' => $request->urdu_name ?? $request->name, // Fallback to name if not provided
            'code' => $request->code,
            'type' => $request->type,
            'unit' => $request->unit,
            'rate' => $request->rate ?? 0,
            'quantity' => $request->quantity ?? 0,
            'created_by' => Auth::id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added successfully.',
                'item' => $item
            ]);
        }

        return redirect()->route('items.index')->with('success', 'Item added successfully.');
    }

    public function update(Request $request, Item $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'urdu_name' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:items,code,' . $item->id,
            'type' => 'nullable|string|max:50',
            'unit' => 'nullable|string|max:20',
            'rate' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|numeric|min:0',
        ]);

        $item->update([
            'name' => $request->name,
            'urdu_name' => $request->urdu_name ?? $request->name,
            'code' => $request->code,
            'type' => $request->type,
            'unit' => $request->unit,
            'rate' => $request->rate ?? 0,
            'quantity' => $request->quantity ?? 0,
        ]);

        return redirect()->route('items.index')->with('success', 'Item updated successfully.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item deleted successfully.');
    }
}
