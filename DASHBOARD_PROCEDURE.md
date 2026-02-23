# Dashboard Tab Procedure

## Overview
This document outlines the procedure for implementing and working with the Dashboard tab in the Commission Shop application, including all required objects and data structures.

## Required Objects for Dashboard Tab

### 1. Transaction Object
The dashboard displays a list of all transactions. Each transaction requires the following fields:

```php
Transaction {
    invoice_number: string (required)  // e.g., "INV-001", "INV-002"
    vendor: string (required)           // e.g., "Annapoorna Stores"
    amount: decimal (required)          // e.g., 54300.00
    date: date (required)               // Transaction date
}
```

**Example:**
- Invoice Number: `INV-001`
- Vendor: `Annapoorna Stores`
- Amount: `₹54,300`

### 2. Invoice Object
The invoice preview section requires the following structure:

#### Company Information
```php
Company {
    name: string (required)        // e.g., "SMX TRADES"
    address: string (required)      // e.g., "Bangalore, Karnataka"
    phone: string (required)       // e.g., "9880438485"
}
```

#### Invoice Details
```php
Invoice {
    bill_no: string (required)      // e.g., "INV-001"
    date: date (required)           // Invoice date
    customer: string (required)     // Customer/Vendor name
    items: array<InvoiceItem>       // Array of invoice items
}
```

### 3. Invoice Item Object
Each item in the invoice requires:

```php
InvoiceItem {
    item_name: string (required)    // e.g., "Carrot"
    quantity: string (required)     // e.g., "20 Bags"
    amount: decimal (required)       // e.g., 24000.00
}
```

**Example Items:**
- Item: `Carrot`, Qty: `20 Bags`, Amount: `₹24,000`
- Item: `Beetroot`, Qty: `25 Bags`, Amount: `₹27,500`
- Item: `Sweet Corn`, Qty: `1 Bag`, Amount: `₹2,400`

## Implementation Procedure

### Step 1: Database Schema Setup

Create migrations for the required tables:

#### Transactions Table
```php
Schema::create('transactions', function (Blueprint $table) {
    $table->id();
    $table->string('invoice_number')->unique();
    $table->string('vendor');
    $table->decimal('amount', 10, 2);
    $table->date('transaction_date');
    $table->timestamps();
});
```

#### Invoices Table
```php
Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->string('bill_no')->unique();
    $table->date('invoice_date');
    $table->string('customer');
    $table->decimal('total_amount', 10, 2);
    $table->timestamps();
});
```

#### Invoice Items Table
```php
Schema::create('invoice_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
    $table->string('item_name');
    $table->string('quantity');
    $table->decimal('amount', 10, 2);
    $table->timestamps();
});
```

#### Company Settings Table
```php
Schema::create('company_settings', function (Blueprint $table) {
    $table->id();
    $table->string('company_name');
    $table->text('address');
    $table->string('phone');
    $table->timestamps();
});
```

### Step 2: Create Models

#### Transaction Model
```php
// app/Models/Transaction.php
class Transaction extends Model
{
    protected $fillable = [
        'invoice_number',
        'vendor',
        'amount',
        'transaction_date'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_date' => 'date'
    ];
}
```

#### Invoice Model
```php
// app/Models/Invoice.php
class Invoice extends Model
{
    protected $fillable = [
        'bill_no',
        'invoice_date',
        'customer',
        'total_amount'
    ];
    
    protected $casts = [
        'invoice_date' => 'date',
        'total_amount' => 'decimal:2'
    ];
    
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
```

#### InvoiceItem Model
```php
// app/Models/InvoiceItem.php
class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_name',
        'quantity',
        'amount'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2'
    ];
    
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
```

### Step 3: Create Controller

```php
// app/Http/Controllers/DashboardController.php
class DashboardController extends Controller
{
    public function index()
    {
        $transactions = Transaction::latest()->take(10)->get();
        $latestInvoice = Invoice::with('items')->latest()->first();
        $company = CompanySetting::first();
        
        return view('dashboard', compact('transactions', 'latestInvoice', 'company'));
    }
}
```

### Step 4: Update Routes

```php
// routes/web.php
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

### Step 5: Update Dashboard View

The dashboard view (`resources/views/dashboard.blade.php` or `welcome.blade.php`) should:

1. **Display Transactions List:**
   - Loop through `$transactions` array
   - Display: Invoice Number, Vendor, Amount

2. **Display Invoice Preview:**
   - Show company information from `$company`
   - Display invoice details: Bill No, Date, Customer
   - Loop through `$latestInvoice->items` to show items table

### Step 6: Data Validation Rules

When creating/updating transactions or invoices, use these validation rules:

#### Transaction Validation
```php
$rules = [
    'invoice_number' => 'required|string|unique:transactions',
    'vendor' => 'required|string|max:255',
    'amount' => 'required|numeric|min:0',
    'transaction_date' => 'required|date'
];
```

#### Invoice Validation
```php
$rules = [
    'bill_no' => 'required|string|unique:invoices',
    'invoice_date' => 'required|date',
    'customer' => 'required|string|max:255',
    'items' => 'required|array|min:1',
    'items.*.item_name' => 'required|string',
    'items.*.quantity' => 'required|string',
    'items.*.amount' => 'required|numeric|min:0'
];
```

## Data Flow Procedure

### Creating a New Transaction/Invoice

1. **User Input:**
   - Fill in invoice number (auto-generated or manual)
   - Select/Enter vendor/customer name
   - Enter transaction date
   - Add items with quantity and amount

2. **Validation:**
   - Validate all required fields
   - Check invoice number uniqueness
   - Verify amounts are positive numbers

3. **Database Storage:**
   - Create transaction record
   - Create invoice record
   - Create invoice item records (one per item)

4. **Dashboard Display:**
   - Transaction appears in "All Transactions" list
   - Latest invoice appears in "Invoice Preview" section

## Required Fields Summary

### Minimum Required for Dashboard Display:

**Transaction Section:**
- ✅ Invoice Number
- ✅ Vendor Name
- ✅ Amount

**Invoice Preview Section:**
- ✅ Company Name
- ✅ Company Address
- ✅ Company Phone
- ✅ Bill Number
- ✅ Invoice Date
- ✅ Customer Name
- ✅ At least one Invoice Item with:
  - Item Name
  - Quantity
  - Amount

## Testing Procedure

1. **Test Transaction Creation:**
   - Create transaction with all required fields
   - Verify it appears in dashboard transactions list

2. **Test Invoice Display:**
   - Create invoice with items
   - Verify invoice preview shows all data correctly

3. **Test Validation:**
   - Try creating transaction without required fields
   - Verify validation errors are displayed

4. **Test Data Relationships:**
   - Verify invoice items are linked to invoice
   - Verify transactions are properly sorted (latest first)

## Notes

- All monetary values should be stored as decimals with 2 decimal places
- Dates should be stored in YYYY-MM-DD format
- Invoice numbers should be unique
- The dashboard displays the latest 10 transactions by default
- The invoice preview shows the most recent invoice
