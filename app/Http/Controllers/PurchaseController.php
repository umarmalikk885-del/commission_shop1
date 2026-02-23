<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Item;
use App\Models\Vendor;
use App\Models\Laga;
use App\Models\CompanySetting;
use App\Models\BankCashTransaction;
use App\Models\User;
use App\Services\BillNumberService;
use App\Services\DashboardMetricsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
    protected $billNumberService;
    protected $metricsService;

    public function __construct(BillNumberService $billNumberService, DashboardMetricsService $metricsService)
    {
        $this->billNumberService = $billNumberService;
        $this->metricsService = $metricsService;
    }

    public function index()
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Exclude blocked vendors - only show active vendors or vendors with null status
        $vendors = Vendor::where(function($query) {
                $query->where('status', '!=', 'blocked')
                      ->orWhereNull('status');
            })
            ->orderBy('name')
            ->get();

        // Filter purchases by user
        $purchaseQuery = Purchase::with(['vendor', 'items']);
        if ($user) {
            $purchaseQuery->where('user_id', $user->id);
        }
        
        $purchases = $purchaseQuery->latest('purchase_date')
            ->latest('id')
            ->paginate(20);

        $pageTotal = $purchases->getCollection()->sum('total_amount');

        // Generate next bill number for preview
        $nextBillNumber = $this->billNumberService->generatePurchaseBillNumber();

        $items = Item::all();

        return view('purchase', compact('vendors', 'purchases', 'pageTotal', 'nextBillNumber', 'items'));
    }

    public function store(Request $request)
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        if ($user && $user->hasRole('User')) {
            // Users can create purchases if they have view purchases permission
            if (!$user->hasPermissionTo('view purchases') && !$user->hasPermissionTo('manage purchases')) {
                return redirect('/purchase')->with('error', 'You do not have permission to create purchases.');
            }
        }
        
        // Handle dynamic vendor creation/lookup from name
        if ($request->has('vendor_name') && !$request->has('vendor_id')) {
            $vendorName = $request->input('vendor_name');
            $vendorCode = $request->input('vendor_code');
            
            $vendor = null;
            if ($vendorCode) {
                $vendor = Vendor::where('code', $vendorCode)->first();
            }
            
            if (!$vendor) {
                $vendor = Vendor::where('name', $vendorName)->first();
            }
            
            if (!$vendor) {
                // Try to create vendor if not found? 
                // Or just fail. Creating is safer if we assume simple names.
                // Let's create it to be helpful for the bakery tool.
                $vendor = Vendor::create([
                    'name' => $vendorName,
                    'mobile' => '0000-0000000', // Dummy
                    'status' => 'active'
                ]);
            }
            $request->merge(['vendor_id' => $vendor->id]);
        }

        // Validate main purchase data
        $data = $request->validate([
            'purchase_id' => ['nullable', 'exists:purchases,id'],
            'purchase_date' => ['required', 'date'],
            'vendor_id' => [
                'required', 
                'exists:vendors,id',
                function ($attribute, $value, $fail) {
                    $vendor = Vendor::find($value);
                    if ($vendor && $vendor->status === 'blocked') {
                        $fail('Cannot create purchase for a blocked vendor.');
                    }
                }
            ],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'laga_code' => ['nullable', 'string', 'max:50'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
            'commission_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $purchaseId = $request->input('purchase_id');
        $isUpdate = !empty($purchaseId);
        
        // Calculate total from all items
        $itemsInput = $request->input('items', []);
        $totalAmount = 0;
        foreach ($itemsInput as $item) {
            $itemAmount = round((float)$item['amount'], 2);
            $totalAmount += $itemAmount;
        }
        $data['total_amount'] = $totalAmount;
        $data['paid_amount'] = round((float) ($request->input('paid_amount') ?? 0), 2);

        // Resolve Laga (Auto-generate code if new)
        $laga = null;
        if (!empty($data['laga_code'])) {
            $laga = Laga::where('code', $data['laga_code'])->first();
        }

        if (!$laga && !empty($data['customer_name'])) {
            $laga = Laga::firstOrCreate(
                ['name' => $data['customer_name']],
                ['status' => 'active']
            );
        }

        if ($laga) {
            $data['laga_id'] = $laga->id;
            $data['laga_code'] = $laga->code;
        }

        // Backward compatibility: populate legacy single-item columns on purchases table
        // using the first item, so NOT NULL constraints (item_name, quantity, rate) are satisfied.
        if (!empty($itemsInput)) {
            $firstItem = $itemsInput[0];
            $data['item_name'] = $firstItem['item_name'] ?? '';
            $data['quantity'] = isset($firstItem['quantity']) ? (float)$firstItem['quantity'] : 0;
            $data['unit'] = $firstItem['unit'] ?? null;
            $data['rate'] = isset($firstItem['rate']) ? (float)$firstItem['rate'] : 0;
        }

        if ($isUpdate) {
            // Update existing purchase
            $purchase = Purchase::findOrFail($purchaseId);
            
            // Calculate commission from vendor's commission rate
            $vendor = Vendor::find($data['vendor_id']);
            if ($vendor && $vendor->commission_rate > 0) {
                $data['commission_amount'] = round(($data['total_amount'] * (float)$vendor->commission_rate) / 100, 2);
            } else {
                $data['commission_amount'] = round((float) ($request->input('commission_amount') ?? 0), 2);
            }
            
            // Ensure paid_amount doesn't exceed total_amount
            if ($data['paid_amount'] > $data['total_amount']) {
                $data['paid_amount'] = $data['total_amount'];
            }

            // Remove purchase_id and items from data array before update
            unset($data['purchase_id'], $data['items']);
            
            $userId = Auth::id();
            $user = $userId ? User::find($userId) : null;
            
            // Use transaction for data integrity
            DB::transaction(function () use ($purchase, $data, $request, $user) {
                $oldPaidAmount = $purchase->paid_amount ?? 0;
                
                $purchase->update($data);
                
                // Delete existing items and create new ones - Optimized with bulk insert
                $purchase->items()->delete();
                $itemsData = [];
                foreach ($request->input('items', []) as $item) {
                    $itemsData[] = [
                        'purchase_id' => $purchase->id,
                        'item_name' => $item['item_name'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'] ?? null,
                        'rate' => $item['rate'],
                        'amount' => round((float)$item['amount'], 2),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($itemsData)) {
                    PurchaseItem::insert($itemsData);
                }
                
                // Handle transaction updates
                $newPaidAmount = $purchase->paid_amount ?? 0;
                $existingTransaction = BankCashTransaction::where('purchase_id', $purchase->id)->first();
                
                if ($newPaidAmount > 0) {
                    if ($existingTransaction) {
                        // Update existing transaction
                        $existingTransaction->update([
                            'transaction_date' => $purchase->purchase_date,
                            'amount' => $newPaidAmount,
                            'description' => 'Purchase payment - ' . ($purchase->bill_number ?? 'N/A'),
                            'notes' => 'Auto-generated from purchase ' . ($purchase->bill_number ?? 'N/A'),
                        ]);
                    } else {
                        // Create new transaction
                        BankCashTransaction::create([
                            'transaction_date' => $purchase->purchase_date,
                            'type' => 'cash',
                            'transaction_type' => 'withdrawal',
                            'amount' => $newPaidAmount,
                            'description' => 'Purchase payment - ' . ($purchase->bill_number ?? 'N/A'),
                            'notes' => 'Auto-generated from purchase ' . ($purchase->bill_number ?? 'N/A'),
                            'purchase_id' => $purchase->id,
                        ]);
                    }
                } elseif ($existingTransaction && $newPaidAmount == 0) {
                    // Delete transaction if paid_amount is now 0
                    $existingTransaction->delete();
                }
            });

            $this->metricsService->clearCache($user);
            
            return redirect('/purchase')->with('success', 'Purchase updated successfully.');
        } else {
            // Create new purchase
            // Generate automatic bill number
            $billNumber = $this->billNumberService->generatePurchaseBillNumber();
            
            // Ensure uniqueness (in case of race condition or non-sequential numbers)
            $attempts = 0;
            while (Purchase::where('bill_number', $billNumber)->exists() && $attempts < 10) {
                // Extract current number and increment
                if (preg_match('/PUR-(\d+)/i', $billNumber, $matches)) {
                    $nextNumber = (int)$matches[1] + 1;
                    $billNumber = 'PUR-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
                } else {
                    // Fallback: use count + 1
                    $billNumber = 'PUR-' . str_pad(Purchase::whereNotNull('bill_number')->count() + 1, 3, '0', STR_PAD_LEFT);
                }
                $attempts++;
            }

            $data['bill_number'] = $billNumber;
            
            // Calculate commission from vendor's commission rate
            $vendor = Vendor::find($data['vendor_id']);
            if ($vendor && $vendor->commission_rate > 0) {
                $data['commission_amount'] = round(($data['total_amount'] * (float)$vendor->commission_rate) / 100, 2);
            } else {
                $data['commission_amount'] = round((float) ($request->input('commission_amount') ?? 0), 2);
            }
            
            // Ensure paid_amount doesn't exceed total_amount
            if ($data['paid_amount'] > $data['total_amount']) {
                $data['paid_amount'] = $data['total_amount'];
            }

            // Remove purchase_id and items from data array before create
            $items = $data['items'];
            unset($data['purchase_id'], $data['items']);

            $userId = Auth::id();
            $user = $userId ? User::find($userId) : null;
            
            // Use transaction for data integrity
            $purchase = DB::transaction(function () use ($data, $items, $user) {
                // Assign user_id to purchase
                $data['user_id'] = $user ? $user->id : null;
                
                $purchase = Purchase::create($data);
                
                // Create purchase items - Optimized with bulk insert
                $itemsData = [];
                foreach ($items as $item) {
                    $itemsData[] = [
                        'purchase_id' => $purchase->id,
                        'item_name' => $item['item_name'],
                        'quantity' => $item['quantity'],
                        'unit' => $item['unit'] ?? null,
                        'rate' => $item['rate'],
                        'amount' => round((float)$item['amount'], 2),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($itemsData)) {
                    PurchaseItem::insert($itemsData);
                }
                
                // Auto-create transaction if paid_amount > 0
                if ($purchase->paid_amount > 0) {
                    BankCashTransaction::create([
                        'transaction_date' => $purchase->purchase_date,
                        'type' => 'cash', // Default to cash for purchases
                        'transaction_type' => 'withdrawal',
                        'amount' => $purchase->paid_amount,
                        'description' => 'Purchase payment - ' . ($purchase->bill_number ?? 'N/A'),
                        'notes' => 'Auto-generated from purchase ' . ($purchase->bill_number ?? 'N/A'),
                        'purchase_id' => $purchase->id,
                    ]);
                }
                
                return $purchase;
            });

            $this->metricsService->clearCache($user);
            
            return redirect('/purchase')->with('success', 'Purchase saved successfully.');
        }
    }

    public function edit($id)
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Filter purchase by user
        $purchaseQuery = Purchase::with(['vendor', 'items']);
        if ($user) {
            $purchaseQuery->where('user_id', $user->id);
        }
        
        $purchase = $purchaseQuery->findOrFail($id);
        
        // Exclude blocked vendors, but include the current purchase's vendor even if blocked (for editing existing purchases)
        $vendors = Vendor::where(function($query) use ($purchase) {
                $query->where('status', '!=', 'blocked')
                      ->orWhereNull('status')
                      ->orWhere('id', $purchase->vendor_id); // Include current vendor even if blocked
            })
            ->orderBy('name')
            ->get();
        
        // Filter purchases by user
        $purchasesQuery = Purchase::with(['vendor', 'items']);
        if ($user) {
            $purchasesQuery->where('user_id', $user->id);
        }
        
        $purchases = $purchasesQuery->latest('purchase_date')
            ->latest('id')
            ->paginate(20);
        
        $pageTotal = $purchases->getCollection()->sum('total_amount');
        $nextBillNumber = $this->billNumberService->generatePurchaseBillNumber();

        return view('purchase', compact('vendors', 'purchases', 'pageTotal', 'nextBillNumber', 'purchase'));
    }

    public function update(Request $request, $id)
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Filter purchase by user
        $purchaseQuery = Purchase::query();
        if ($user) {
            $purchaseQuery->where('user_id', $user->id);
        }
        
        $purchase = $purchaseQuery->findOrFail($id);
        
        if ($user && $user->hasRole('User')) {
            // Users can update their own purchases if they have view purchases permission
            if (!$user->hasPermissionTo('view purchases') && !$user->hasPermissionTo('manage purchases')) {
                return redirect('/purchase')->with('error', 'You do not have permission to update purchases.');
            }
        }
        
        $data = $request->validate([
            'purchase_date' => ['required', 'date'],
            'vendor_id' => [
                'required', 
                'exists:vendors,id',
                function ($attribute, $value, $fail) use ($purchase) {
                    $vendor = Vendor::find($value);
                    // Allow keeping the same vendor even if blocked (for existing purchases)
                    // But prevent changing to a different blocked vendor
                    if ($vendor && $vendor->status === 'blocked' && $value != $purchase->vendor_id) {
                        $fail('Cannot change purchase to a blocked vendor.');
                    }
                }
            ],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_name' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit' => ['nullable', 'string', 'max:50'],
            'items.*.rate' => ['required', 'numeric', 'min:0'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
            'commission_amount' => ['nullable', 'numeric', 'min:0'],
            'paid_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        // Calculate total from all items
        $itemsInput = $request->input('items', []);
        $totalAmount = 0;
        foreach ($itemsInput as $item) {
            $itemAmount = round((float)$item['amount'], 2);
            $totalAmount += $itemAmount;
        }
        $data['total_amount'] = $totalAmount;
        $data['paid_amount'] = round((float) ($request->input('paid_amount') ?? 0), 2);

        // Backward compatibility: also keep legacy single-item fields in sync (first item)
        if (!empty($itemsInput)) {
            $firstItem = $itemsInput[0];
            $data['item_name'] = $firstItem['item_name'] ?? '';
            $data['quantity'] = isset($firstItem['quantity']) ? (float)$firstItem['quantity'] : 0;
            $data['unit'] = $firstItem['unit'] ?? null;
            $data['rate'] = isset($firstItem['rate']) ? (float)$firstItem['rate'] : 0;
        }
        
        // Calculate commission from vendor's commission rate
        $vendor = Vendor::find($data['vendor_id']);
        if ($vendor && $vendor->commission_rate > 0) {
            $data['commission_amount'] = round(($data['total_amount'] * (float)$vendor->commission_rate) / 100, 2);
        } else {
            $data['commission_amount'] = round((float) ($request->input('commission_amount') ?? 0), 2);
        }
        
        // Ensure paid_amount doesn't exceed total_amount
        if ($data['paid_amount'] > $data['total_amount']) {
            $data['paid_amount'] = $data['total_amount'];
        }

        // Remove items from data array before update
        $items = $data['items'];
        unset($data['items']);

        $purchase->update($data);
        
        // Delete existing items and create new ones - Optimized with bulk insert
        $purchase->items()->delete();
        $itemsData = [];
        foreach ($items as $item) {
            $itemsData[] = [
                'purchase_id' => $purchase->id,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit' => $item['unit'] ?? null,
                'rate' => $item['rate'],
                'amount' => round((float)$item['amount'], 2),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        if (!empty($itemsData)) {
            PurchaseItem::insert($itemsData);
        }

        return redirect('/purchase')->with('success', 'Purchase updated successfully.');
    }

    public function destroy($id)
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Filter purchase by user
        $purchaseQuery = Purchase::query();
        if ($user) {
            $purchaseQuery->where('user_id', $user->id);
        }
        
        $purchase = $purchaseQuery->findOrFail($id);
        
        if ($user && $user->hasRole('User')) {
            // Users can delete their own purchases if they have view purchases permission
            if (!$user->hasPermissionTo('view purchases') && !$user->hasPermissionTo('manage purchases')) {
                return redirect('/purchase')->with('error', 'You do not have permission to delete purchases.');
            }
        }
        
        $purchase->delete();

        $this->metricsService->clearCache($user);
        
        return redirect('/purchase')->with('success', 'Purchase deleted successfully.');
    }

    public function print($id)
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Filter purchase by user
        $purchaseQuery = Purchase::with(['vendor', 'items']);
        if ($user) {
            $purchaseQuery->where('user_id', $user->id);
        }
        
        $purchase = $purchaseQuery->findOrFail($id);
        $company = CompanySetting::current();
        return view('purchase.print', compact('purchase', 'company'));
    }

    public function getItems($id)
    {
        $userId = Auth::id();
        $user = $userId ? User::find($userId) : null;
        
        // Filter purchase by user
        $purchaseQuery = Purchase::with('items');
        if ($user) {
            $purchaseQuery->where('user_id', $user->id);
        }
        
        $purchase = $purchaseQuery->findOrFail($id);
        
        // Check if purchase has items (new structure) or single item (old structure)
        if ($purchase->items && $purchase->items->count() > 0) {
            return response()->json([
                'items' => $purchase->items->map(function($item) {
                    return [
                        'item_name' => $item->item_name,
                        'quantity' => $item->quantity,
                        'unit' => $item->unit,
                        'rate' => $item->rate,
                        'amount' => $item->amount
                    ];
                })
            ]);
        } else {
            // Backward compatibility: return single item
            return response()->json([
                'item_name' => $purchase->item_name,
                'quantity' => $purchase->quantity,
                'unit' => $purchase->unit,
                'rate' => $purchase->rate,
                'amount' => $purchase->total_amount
            ]);
        }
    }
}
