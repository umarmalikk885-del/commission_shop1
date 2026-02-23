<?php

namespace App\Http\Controllers;

use App\Models\BakriBook;
use App\Models\BakriBookItem;
use App\Models\BakriBookTraderDetail;
use App\Models\BakriBookTransaction;
use App\Models\Item;
use App\Models\Laga;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Display the payment page with all records.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Determine current application language for layout (LTR/RTL)
        $appLanguage = app()->getLocale();
        if ($appLanguage === null) {
            $appLanguage = 'ur';
        } elseif (str_starts_with($appLanguage, 'ur')) {
            $appLanguage = 'ur';
        } else {
            $appLanguage = 'ur';
        }

        // Get all bakri books for the current user
        $bakriBooks = BakriBook::forUser($user?->id)
            ->with(['items', 'traderDetails', 'transactions'])
            ->orderBy('record_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Get the current record (first one or create empty)
        $currentRecord = $bakriBooks->first();
        $totalRecords = $bakriBooks->count();
        $currentIndex = $totalRecords > 0 ? 1 : 0;

        $items = Item::all();

        return view('payment', compact(
            'appLanguage',
            'bakriBooks',
            'currentRecord',
            'totalRecords',
            'currentIndex',
            'items'
        ));
    }

    /**
     * Display the bakery page.
     */
    public function bakery()
    {
        $user = Auth::user();
        
        // Determine current application language for layout (LTR/RTL)
        $appLanguage = app()->getLocale();
        if ($appLanguage === null) {
            $appLanguage = 'ur';
        } elseif (str_starts_with($appLanguage, 'ur')) {
            $appLanguage = 'ur';
        } else {
            $appLanguage = 'ur';
        }

        // Get all bakri books for the current user
        $bakriBooks = BakriBook::forUser($user?->id)
            ->with(['items', 'traderDetails', 'transactions'])
            ->orderBy('record_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        // Get the current record (first one or create empty)
        $currentRecord = $bakriBooks->first();
        $totalRecords = $bakriBooks->count();
        $currentIndex = $totalRecords > 0 ? 1 : 0;

        $items = Item::all();
        $lagas = Laga::all();
        $people = Person::all();

        return view('bakery', compact(
            'appLanguage',
            'bakriBooks',
            'currentRecord',
            'totalRecords',
            'currentIndex',
            'items',
            'lagas',
            'people'
        ));
    }

    /**
     * Store a new bakri book record.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'record_date' => 'required|date',
            'trader' => 'nullable|string|max:255',
            'goat_number' => 'nullable|string|max:255',
            'truck_number' => 'nullable|string|max:255',
            'raw_goat' => 'nullable|numeric',
            'fare' => 'nullable|numeric',
            'food_rent' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'labor' => 'nullable|numeric',
            'mashiana' => 'nullable|numeric',
            'stamp' => 'nullable|numeric',
            'other_expenses' => 'nullable|numeric',
            'balance1' => 'nullable|numeric',
            'balance2' => 'nullable|numeric',
            'additional_details' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Create main bakri book record
            $bakriBook = BakriBook::create([
                'user_id' => Auth::id(),
                'record_date' => $validated['record_date'],
                'trader' => $validated['trader'] ?? null,
                'goat_number' => $validated['goat_number'] ?? null,
                'truck_number' => $validated['truck_number'] ?? null,
                'raw_goat' => $validated['raw_goat'] ?? 0,
                'fare' => $validated['fare'] ?? 0,
                'food_rent' => $validated['food_rent'] ?? 0,
                'commission' => $validated['commission'] ?? 0,
                'labor' => $validated['labor'] ?? 0,
                'mashiana' => $validated['mashiana'] ?? 0,
                'stamp' => $validated['stamp'] ?? 0,
                'other_expenses' => $validated['other_expenses'] ?? 0,
                'balance1' => $validated['balance1'] ?? 0,
                'balance2' => $validated['balance2'] ?? 0,
                'additional_details' => $validated['additional_details'] ?? null,
            ]);

            // Calculate totals
            $bakriBook->calculateTotalExpenses();
            $bakriBook->calculateNetGoat();
            $bakriBook->save();

            // Store items (first table)
            if ($request->has('items')) {
                foreach ($request->input('items') as $index => $itemData) {
                    if (!empty($itemData['code']) || !empty($itemData['item_type']) || !empty($itemData['quantity'])) {
                        
                        // Ensure Item exists in master list
                        $itemName = $itemData['item_type'] ?? null;
                        if ($itemName) {
                            $existingItem = Item::where('name', $itemName)->orWhere('urdu_name', $itemName)->first();
                            if (!$existingItem) {
                                // Determine Code
                                $newCode = $itemData['code'] ?? null;
                                if (!$newCode || Item::where('code', $newCode)->exists()) {
                                    $lastItem = Item::latest('id')->first();
                                    $nextId = $lastItem ? $lastItem->id + 1 : 1;
                                    $newCode = 'N-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
                                }
                                
                                Item::create([
                                    'name' => $itemName,
                                    'code' => $newCode,
                                    'urdu_name' => $itemName,
                                    'type' => 'other'
                                ]);
                            }
                        }

                        BakriBookItem::create([
                            'bakri_book_id' => $bakriBook->id,
                            'code' => $itemData['code'] ?? null,
                            'item_date' => $itemData['item_date'] ?? null,
                            'item_type' => $itemData['item_type'] ?? null,
                            'packing_code' => $itemData['packing_code'] ?? null,
                            'packing' => $itemData['packing'] ?? null,
                            'quantity' => $itemData['quantity'] ?? 0,
                            'labor_rate' => $itemData['labor_rate'] ?? 0,
                            'labor' => $itemData['labor'] ?? 0,
                            'labor_transport' => $itemData['labor_transport'] ?? 0,
                            'commission_rate' => $itemData['commission_rate'] ?? 0,
                            'marker' => $itemData['marker'] ?? null,
                            'row_order' => $index,
                        ]);
                    }
                }
            }

            // Store transactions (third table) - Mapped from view field names
            if ($request->has('transactions')) {
                foreach ($request->input('transactions') as $index => $transData) {
                    // Check if row has data using view field names
                    if (!empty($transData['transaction_date']) || !empty($transData['book_code']) || !empty($transData['daily_quantity'])) {
                        BakriBookTransaction::create([
                            'bakri_book_id' => $bakriBook->id,
                            'transaction_date' => $transData['transaction_date'] ?? null,
                            'book_code' => $transData['book_code'] ?? null,
                            'book' => $transData['book'] ?? null,
                            'trader_quantity' => $transData['daily_quantity'] ?? 0,
                            'trader_rate' => $transData['daily_rate'] ?? 0,
                            'purchaser_rate' => $transData['laga_rate'] ?? 0,
                            'trader_amount' => $transData['daily_amount'] ?? 0,
                            'book_quantity' => $transData['book_quantity'] ?? 0,
                            'book_rate' => $transData['book_rate'] ?? 0,
                            'payment_rate' => $transData['laga_rate'] ?? 0, // Using laga_rate for payment_rate as well
                            'book_amount' => $transData['row_total'] ?? 0,
                            'row_order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record saved successfully!',
                    'id' => $bakriBook->id,
                    'record' => $bakriBook
                ]);
            }

            return redirect()->route('payment')->with('success', 'Record saved successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error saving record: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error saving record: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update an existing bakri book record.
     */
    public function update(Request $request, $id)
    {
        $bakriBook = BakriBook::findOrFail($id);

        $validated = $request->validate([
            'record_date' => 'required|date',
            'trader' => 'nullable|string|max:255',
            'goat_number' => 'nullable|string|max:255',
            'truck_number' => 'nullable|string|max:255',
            'raw_goat' => 'nullable|numeric',
            'fare' => 'nullable|numeric',
            'food_rent' => 'nullable|numeric',
            'commission' => 'nullable|numeric',
            'labor' => 'nullable|numeric',
            'mashiana' => 'nullable|numeric',
            'stamp' => 'nullable|numeric',
            'other_expenses' => 'nullable|numeric',
            'balance1' => 'nullable|numeric',
            'balance2' => 'nullable|numeric',
            'additional_details' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Update main record
            $bakriBook->update([
                'record_date' => $validated['record_date'],
                'trader' => $validated['trader'] ?? null,
                'goat_number' => $validated['goat_number'] ?? null,
                'truck_number' => $validated['truck_number'] ?? null,
                'raw_goat' => $validated['raw_goat'] ?? 0,
                'fare' => $validated['fare'] ?? 0,
                'food_rent' => $validated['food_rent'] ?? 0,
                'commission' => $validated['commission'] ?? 0,
                'labor' => $validated['labor'] ?? 0,
                'mashiana' => $validated['mashiana'] ?? 0,
                'stamp' => $validated['stamp'] ?? 0,
                'other_expenses' => $validated['other_expenses'] ?? 0,
                'balance1' => $validated['balance1'] ?? 0,
                'balance2' => $validated['balance2'] ?? 0,
                'additional_details' => $validated['additional_details'] ?? null,
            ]);

            // Recalculate totals
            $bakriBook->calculateTotalExpenses();
            $bakriBook->calculateNetGoat();
            $bakriBook->save();

            // Delete existing items and recreate
            $bakriBook->items()->delete();
            if ($request->has('items')) {
                foreach ($request->input('items') as $index => $itemData) {
                    if (!empty($itemData['code']) || !empty($itemData['item_type']) || !empty($itemData['quantity'])) {
                        
                        // Ensure Item exists in master list
                        $itemName = $itemData['item_type'] ?? null;
                        if ($itemName) {
                            $existingItem = Item::where('name', $itemName)->orWhere('urdu_name', $itemName)->first();
                            if (!$existingItem) {
                                // Determine Code
                                $newCode = $itemData['code'] ?? null;
                                if (!$newCode || Item::where('code', $newCode)->exists()) {
                                    $lastItem = Item::latest('id')->first();
                                    $nextId = $lastItem ? $lastItem->id + 1 : 1;
                                    $newCode = 'N-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
                                }
                                
                                Item::create([
                                    'name' => $itemName,
                                    'code' => $newCode,
                                    'urdu_name' => $itemName,
                                    'type' => 'other'
                                ]);
                            }
                        }

                        BakriBookItem::create([
                            'bakri_book_id' => $bakriBook->id,
                            'code' => $itemData['code'] ?? null,
                            'item_date' => $itemData['item_date'] ?? null,
                            'item_type' => $itemData['item_type'] ?? null,
                            'packing_code' => $itemData['packing_code'] ?? null,
                            'packing' => $itemData['packing'] ?? null,
                            'quantity' => $itemData['quantity'] ?? 0,
                            'labor_rate' => $itemData['labor_rate'] ?? 0,
                            'labor' => $itemData['labor'] ?? 0,
                            'labor_transport' => $itemData['labor_transport'] ?? 0,
                            'commission_rate' => $itemData['commission_rate'] ?? 0,
                            'marker' => $itemData['marker'] ?? null,
                            'row_order' => $index,
                        ]);
                    }
                }
            }

            // Delete existing transactions and recreate - Mapped from view field names
            $bakriBook->transactions()->delete();
            if ($request->has('transactions')) {
                foreach ($request->input('transactions') as $index => $transData) {
                    // Check if row has data using view field names
                    if (!empty($transData['transaction_date']) || !empty($transData['book_code']) || !empty($transData['daily_quantity'])) {
                        BakriBookTransaction::create([
                            'bakri_book_id' => $bakriBook->id,
                            'transaction_date' => $transData['transaction_date'] ?? null,
                            'book_code' => $transData['book_code'] ?? null,
                            'book' => $transData['book'] ?? null,
                            'trader_quantity' => $transData['daily_quantity'] ?? 0,
                            'trader_rate' => $transData['daily_rate'] ?? 0,
                            'purchaser_rate' => $transData['laga_rate'] ?? 0,
                            'trader_amount' => $transData['daily_amount'] ?? 0,
                            'book_quantity' => $transData['book_quantity'] ?? 0,
                            'book_rate' => $transData['book_rate'] ?? 0,
                            'payment_rate' => $transData['laga_rate'] ?? 0, // Using laga_rate for payment_rate as well
                            'book_amount' => $transData['row_total'] ?? 0,
                            'row_order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Record updated successfully!',
                    'id' => $bakriBook->id,
                    'record' => $bakriBook
                ]);
            }

            return redirect()->route('payment')->with('success', 'Record updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating record: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Error updating record: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Delete a bakri book record.
     */
    public function destroy($id)
    {
        $bakriBook = BakriBook::findOrFail($id);
        
        DB::beginTransaction();
        try {
            // Delete related records (handled by cascade, but explicit for safety)
            $bakriBook->items()->delete();
            $bakriBook->traderDetails()->delete();
            $bakriBook->transactions()->delete();
            $bakriBook->delete();

            DB::commit();
            return redirect()->route('payment')->with('success', 'Record deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }

    /**
     * Get a specific record as JSON (for AJAX navigation).
     */
    public function show($id)
    {
        $bakriBook = BakriBook::with(['items', 'traderDetails', 'transactions'])->findOrFail($id);
        return response()->json($bakriBook);
    }

    /**
     * Navigate to a specific record by index.
     */
    public function navigate(Request $request)
    {
        $user = Auth::user();
        $direction = $request->input('direction'); // first, prev, next, last
        $currentId = $request->input('current_id');

        $query = BakriBook::forUser($user?->id)->orderBy('id', 'asc');
        $allRecords = $query->get();
        $totalRecords = $allRecords->count();

        if ($totalRecords === 0) {
            return response()->json(['record' => null, 'index' => 0, 'total' => 0]);
        }

        $currentIndex = $currentId ? $allRecords->search(fn($r) => $r->id == $currentId) : 0;
        if ($currentIndex === false) $currentIndex = 0;

        switch ($direction) {
            case 'first':
                $newIndex = 0;
                break;
            case 'prev':
                $newIndex = max(0, $currentIndex - 1);
                break;
            case 'next':
                $newIndex = min($totalRecords - 1, $currentIndex + 1);
                break;
            case 'last':
                $newIndex = $totalRecords - 1;
                break;
            default:
                $newIndex = $currentIndex;
        }

        $record = $allRecords[$newIndex];
        $record->load(['items', 'traderDetails', 'transactions']);

        return response()->json([
            'record' => $record,
            'index' => $newIndex + 1,
            'total' => $totalRecords,
        ]);
    }

    /**
     * Comprehensive search across owners (BakriBook) and purchasers (BakriBookTransaction) with items.
     */
    public function searchRecords(Request $request)
    {
        $validated = $request->validate([
            'owner' => 'nullable|string|max:255',
            'purchaser' => 'nullable|string|max:255',
            'product' => 'nullable|string|max:255',
            'from' => 'nullable|date',
            'to' => 'nullable|date',
        ]);
        
        if (!empty($validated['from']) && !empty($validated['to']) && $validated['from'] > $validated['to']) {
            return back()->with('error', 'Invalid date range: From must be before To')->withInput();
        }
        
        $userId = Auth::id();
        $query = DB::table('bakri_books as b')
            ->leftJoin('bakri_book_transactions as t', 't.bakri_book_id', '=', 'b.id')
            ->leftJoin('bakri_book_items as i', 'i.bakri_book_id', '=', 'b.id')
            ->select([
                'b.id',
                'b.trader',
                'b.goat_number',
                'b.truck_number',
                'b.record_date',
                'i.item_type',
                'i.code as item_code',
                't.book as purchaser_name',
                't.book_code',
                't.transaction_date',
            ])
            ->where('b.user_id', $userId);
        
        if (!empty($validated['owner'])) {
            $owner = mb_strtolower($validated['owner']);
            $query->whereRaw('LOWER(b.trader) LIKE ?', ['%' . $owner . '%']);
        }
        if (!empty($validated['purchaser'])) {
            $purchaser = mb_strtolower($validated['purchaser']);
            $query->whereRaw('LOWER(t.book) LIKE ?', ['%' . $purchaser . '%']);
        }
        if (!empty($validated['product'])) {
            $product = mb_strtolower($validated['product']);
            $query->where(function ($q) use ($product) {
                $q->whereRaw('LOWER(i.item_type) LIKE ?', ['%' . $product . '%'])
                  ->orWhereRaw('LOWER(i.code) LIKE ?', ['%' . $product . '%']);
            });
        }
        if (!empty($validated['from'])) {
            $query->where(function ($q) use ($validated) {
                $q->where('b.record_date', '>=', $validated['from'])
                  ->orWhere('t.transaction_date', '>=', $validated['from']);
            });
        }
        if (!empty($validated['to'])) {
            $query->where(function ($q) use ($validated) {
                $q->where('b.record_date', '<=', $validated['to'])
                  ->orWhere('t.transaction_date', '<=', $validated['to']);
            });
        }
        
        $perPage = max(10, min(100, (int)($request->input('per_page', 25))));
        $results = $query->orderBy('b.record_date', 'desc')->orderBy('b.id', 'desc')->paginate($perPage)->withQueryString();
        
        if ($request->input('export') === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="records_export.csv"',
            ];
            $callback = function () use ($results) {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['Owner', 'Truck No', 'Goat No', 'Owner Date', 'Purchaser', 'Book Code', 'Transaction Date', 'Item Type', 'Item Code']);
                foreach ($results->items() as $r) {
                    fputcsv($out, [
                        $r->trader,
                        $r->truck_number,
                        $r->goat_number,
                        $r->record_date,
                        $r->purchaser_name,
                        $r->book_code,
                        $r->transaction_date,
                        $r->item_type,
                        $r->item_code,
                    ]);
                }
                fclose($out);
            };
            return response()->stream($callback, 200, $headers);
        }
        
        return view('records_search', [
            'results' => $results,
            'filters' => [
                'owner' => $validated['owner'] ?? '',
                'purchaser' => $validated['purchaser'] ?? '',
                'product' => $validated['product'] ?? '',
                'from' => $validated['from'] ?? '',
                'to' => $validated['to'] ?? '',
                'per_page' => $perPage,
            ],
        ]);
    }

    public function liveSearchRecords(Request $request)
    {
        $q = trim((string)$request->input('q', ''));
        $userId = Auth::id();

        if ($q === '' || !$userId) {
            return response()->json([
                'query' => $q,
                'people' => [],
            ]);
        }

        $qLower = mb_strtolower($q);
        $like = '%' . $qLower . '%';

        $rows = DB::table('bakri_books as b')
            ->leftJoin('bakri_book_transactions as t', 't.bakri_book_id', '=', 'b.id')
            ->leftJoin('lagas as lg', 'lg.code', '=', 't.book_code')
            ->select([
                'b.id as book_id',
                'b.trader',
                'b.goat_number',
                'b.truck_number',
                'b.record_date',
                't.book as purchaser_name',
                't.book_code as purchaser_code',
                't.transaction_date',
                'lg.mobile',
                'lg.address',
                'lg.location',
                'lg.contact_number',
            ])
            ->where('b.user_id', $userId)
            ->where(function ($query) use ($like) {
                $query->whereRaw('LOWER(b.trader) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(t.book) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(b.goat_number) LIKE ?', [$like])
                    ->orWhereRaw('LOWER(b.truck_number) LIKE ?', [$like])
                    ->orWhereRaw('CAST(b.id as CHAR) LIKE ?', [$like]);
            })
            ->orderBy('b.record_date', 'desc')
            ->orderBy('b.id', 'desc')
            ->limit(100)
            ->get();

        $people = [];

        foreach ($rows as $row) {
            $candidateNames = [];
            if ($row->trader) {
                $candidateNames[] = $row->trader;
            }
            if ($row->purchaser_name) {
                $candidateNames[] = $row->purchaser_name;
            }

            $primaryName = null;
            foreach ($candidateNames as $name) {
                if (mb_stripos(mb_strtolower($name), $qLower) !== false) {
                    $primaryName = $name;
                    break;
                }
            }

            if ($primaryName === null) {
                $primaryName = $row->purchaser_name ?: $row->trader;
            }

            if ($primaryName === null || $primaryName === '') {
                continue;
            }

            $key = mb_strtolower($primaryName);

            if (!isset($people[$key])) {
                $people[$key] = [
                    'name' => $primaryName,
                    'is_owner' => false,
                    'is_purchaser' => false,
                    'contact' => [
                        'mobile' => $row->mobile,
                        'address' => $row->address,
                        'location' => $row->location,
                        'contact_number' => $row->contact_number,
                    ],
                    'records' => [],
                ];
            }

            $isOwnerHere = $row->trader && mb_strtolower($row->trader) === $key;
            $isPurchaserHere = $row->purchaser_name && mb_strtolower($row->purchaser_name) === $key;

            if ($isOwnerHere) {
                $people[$key]['is_owner'] = true;
            }
            if ($isPurchaserHere) {
                $people[$key]['is_purchaser'] = true;
            }

            if ($row->mobile || $row->address || $row->location || $row->contact_number) {
                $people[$key]['contact'] = [
                    'mobile' => $row->mobile,
                    'address' => $row->address,
                    'location' => $row->location,
                    'contact_number' => $row->contact_number,
                ];
            }

            $people[$key]['records'][] = [
                'book_id' => $row->book_id,
                'role' => $isPurchaserHere ? 'purchaser' : 'owner',
                'record_date' => $row->record_date,
                'goat_number' => $row->goat_number,
                'truck_number' => $row->truck_number,
                'transaction_date' => $row->transaction_date,
                'purchaser_code' => $row->purchaser_code,
            ];
        }

        foreach ($people as $key => $person) {
            if ($person['is_owner'] && $person['is_purchaser']) {
                $people[$key]['role_label'] = 'dual';
            } elseif ($person['is_purchaser']) {
                $people[$key]['role_label'] = 'purchaser';
            } else {
                $people[$key]['role_label'] = 'owner';
            }
        }

        return response()->json([
            'query' => $q,
            'people' => array_values($people),
        ]);
    }
}
