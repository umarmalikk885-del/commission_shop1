<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use App\Models\Purchase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::all();
        return view('vendors', compact('vendors'));
    }

    public function create()
    {
        return view('vendors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,blocked'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        Vendor::create($data);
        return redirect('/vendors')->with('success', 'Vendor added successfully');
    }

    public function edit($id)
    {
        $vendor = Vendor::findOrFail($id);
        return view('vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['nullable', 'string'],
            'status' => ['required', 'in:active,blocked'],
            'commission_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $vendor->update($data);
        return redirect('/vendors')->with('success', 'Vendor updated');
    }

    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        
        // Check if vendor has related records
        $hasPurchases = Purchase::where('vendor_id', $id)->exists();
        
        if ($hasPurchases) {
            // Cannot delete vendor with related records
            $message = 'Cannot delete vendor. This vendor has purchases associated with it. Please remove or reassign these records first.';
            
            return redirect('/vendors')->with('error', $message);
        }
        
        // Safe to delete
        $vendor->delete();
        return redirect('/vendors')->with('success', 'Vendor deleted successfully');
    }
}
