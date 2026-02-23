<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packing;

class PackingController extends Controller
{
    public function index()
    {
        return response()->json(Packing::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255|unique:packings,code',
            'labor' => 'required|numeric|min:0',
            'details' => 'nullable|string',
        ]);

        $packing = Packing::create($validated);

        return response()->json([
            'message' => 'Packing added successfully',
            'packing' => $packing
        ], 201);
    }

    public function destroy($id)
    {
        $packing = Packing::findOrFail($id);
        $packing->delete();
        return response()->json(['message' => 'Packing deleted successfully']);
    }
}
