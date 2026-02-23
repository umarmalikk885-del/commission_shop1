<?php

namespace App\Http\Controllers;

use App\Http\Requests\Inventory\StoreUpdateItemRequest;
use App\Http\Requests\Inventory\UpdateStockRequest;
use App\Models\InventoryLog;
use App\Models\MarketItem;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = MarketItem::with('productOwner');

        if ($user->hasRole('Product Owner')) {
            $query->where('product_owner_id', $user->id);
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($supplierId = $request->get('supplier_id')) {
            $query->where('product_owner_id', $supplierId);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('name')->paginate(50);

        $suppliers = User::role('Product Owner')->orderBy('name')->get();

        $canExport = $user->hasRole('Mandi Owner') || $user->hasRole('Super Admin');
        $canEditProducts = $user->hasAnyRole('Mandi Owner', 'Product Owner', 'Super Admin');
        $canAdjustStock = $user->hasAnyRole('Mandi Owner', 'Purchaser', 'Super Admin');

        return view('inventory.index', compact(
            'items',
            'suppliers',
            'canExport',
            'canEditProducts',
            'canAdjustStock'
        ));
    }

    public function store(StoreUpdateItemRequest $request)
    {
        $user = $request->user();

        $data = $request->validated();

        if ($user->hasRole('Product Owner')) {
            $data['product_owner_id'] = $user->id;
        } elseif (empty($data['product_owner_id'])) {
            $data['product_owner_id'] = $user->id;
        }

        $item = MarketItem::create($data);

        InventoryLog::create([
            'market_item_id' => $item->id,
            'user_id' => $user->id,
            'action' => 'create',
            'quantity_after' => $item->quantity,
            'price_after' => $item->price_per_unit,
            'meta' => ['message' => 'created'],
        ]);

        return redirect()->route('inventory.index')->with('success', 'Item created.');
    }

    public function update(MarketItem $item, StoreUpdateItemRequest $request)
    {
        $user = $request->user();

        if ($user->hasRole('Product Owner') && $item->product_owner_id !== $user->id) {
            abort(403);
        }

        $beforeQuantity = $item->quantity;
        $beforePrice = $item->price_per_unit;

        $item->update($request->validated());

        InventoryLog::create([
            'market_item_id' => $item->id,
            'user_id' => $user->id,
            'action' => 'update',
            'quantity_before' => $beforeQuantity,
            'quantity_after' => $item->quantity,
            'price_before' => $beforePrice,
            'price_after' => $item->price_per_unit,
            'meta' => ['message' => 'updated'],
        ]);

        return redirect()->route('inventory.index')->with('success', 'Item updated.');
    }

    public function adjustStock(MarketItem $item, UpdateStockRequest $request)
    {
        $user = $request->user();
        $delta = (float) $request->input('quantity_delta');

        $before = $item->quantity;
        $after = $before + $delta;

        if ($after < 0) {
            return back()->withErrors(['quantity_delta' => 'Stock cannot be negative.'])->withInput();
        }

        $item->quantity = $after;
        if ($after <= 0) {
            $item->status = 'out';
        } elseif ($after <= 10) {
            $item->status = 'low';
        } else {
            $item->status = 'available';
        }
        if (!$item->available_from && $after > 0) {
            $item->available_from = now();
        }
        $item->save();

        InventoryLog::create([
            'market_item_id' => $item->id,
            'user_id' => $user->id,
            'action' => 'adjust_stock',
            'quantity_before' => $before,
            'quantity_after' => $after,
            'meta' => ['reason' => $request->input('reason')],
        ]);

        return redirect()->route('inventory.index')->with('success', 'Stock updated.');
    }

    public function export(Request $request): StreamedResponse
    {
        $user = $request->user();

        if (!$user->hasRole('Mandi Owner')) {
            abort(403);
        }

        $items = MarketItem::with('productOwner')->orderBy('name')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=inventory_export.csv',
        ];

        return response()->stream(function () use ($items) {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'ID',
                'Name',
                'Type',
                'Product Owner',
                'Quantity',
                'Unit',
                'Price per unit',
                'Status',
                'Available From',
            ]);

            foreach ($items as $item) {
                fputcsv($out, [
                    $item->id,
                    $item->name,
                    $item->type,
                    optional($item->productOwner)->name,
                    $item->quantity,
                    $item->unit,
                    $item->price_per_unit,
                    $item->status,
                    optional($item->available_from)->toDateTimeString(),
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }
}
