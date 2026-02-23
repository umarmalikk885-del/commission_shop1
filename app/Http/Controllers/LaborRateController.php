<?php

namespace App\Http\Controllers;

use App\Models\LaborRate;
use Illuminate\Http\Request;

class LaborRateController extends Controller
{
    /**
     * Get all labor rates for the current user.
     */
    public function index()
    {
        $user = auth()->user();
        $laborRates = LaborRate::forUser($user?->id)
            ->active()
            ->orderBy('item_name')
            ->get();

        return response()->json($laborRates);
    }

    /**
     * Store a new labor rate.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_code' => 'nullable|string|max:50',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'labor_rate' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        $laborRate = LaborRate::create([
            'user_id' => auth()->id(),
            'item_code' => $validated['item_code'] ?? null,
            'item_name' => $validated['item_name'],
            'category' => $validated['category'] ?? null,
            'labor_rate' => $validated['labor_rate'],
            'unit' => $validated['unit'] ?? 'kg',
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Labor rate saved successfully!',
            'data' => $laborRate,
        ]);
    }

    /**
     * Update an existing labor rate.
     */
    public function update(Request $request, $id)
    {
        $laborRate = LaborRate::findOrFail($id);

        $validated = $request->validate([
            'item_code' => 'nullable|string|max:50',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'labor_rate' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'is_active' => 'nullable|boolean',
        ]);

        $laborRate->update([
            'item_code' => $validated['item_code'] ?? null,
            'item_name' => $validated['item_name'],
            'category' => $validated['category'] ?? null,
            'labor_rate' => $validated['labor_rate'],
            'unit' => $validated['unit'] ?? 'kg',
            'notes' => $validated['notes'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Labor rate updated successfully!',
            'data' => $laborRate,
        ]);
    }

    /**
     * Delete a labor rate.
     */
    public function destroy($id)
    {
        $laborRate = LaborRate::findOrFail($id);
        $laborRate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Labor rate deleted successfully!',
        ]);
    }

    /**
     * Get labor rate by item code (for auto-fill in payment page).
     */
    public function getByCode(Request $request)
    {
        $code = $request->input('code');
        $user = auth()->user();

        $laborRate = LaborRate::getRateByCode($code, $user?->id);

        if ($laborRate) {
            return response()->json([
                'success' => true,
                'data' => $laborRate,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Labor rate not found for this code.',
        ]);
    }

    /**
     * Search labor rates by name.
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $user = auth()->user();

        $laborRates = LaborRate::active()
            ->forUser($user?->id)
            ->where(function ($q) use ($query) {
                $q->where('item_name', 'like', "%{$query}%")
                  ->orWhere('item_code', 'like', "%{$query}%");
            })
            ->limit(10)
            ->get();

        return response()->json($laborRates);
    }
}
