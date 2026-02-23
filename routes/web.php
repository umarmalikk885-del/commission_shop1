<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VendorsController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PurchaserDetailController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LaborRateController;
use App\Http\Controllers\RecoveryController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\RowBackupController;
use App\Http\Controllers\RokadController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\InventoryController;

// Authentication routes (login, register, password reset, etc.)
require __DIR__.'/auth.php';

// Root -> always redirect to login page
Route::get('/', function () {
    return redirect('/login');
});

// Application routes WITH authentication and role-based access control
Route::middleware(['auth', 'role.check'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Index/Trader page - People Management
    Route::get('/index', [PersonController::class, 'index'])->name('index');
    Route::get('/people', [PersonController::class, 'index'])->name('people.index');
    Route::get('/people/create', [PersonController::class, 'create'])->name('people.create');
    Route::post('/people', [PersonController::class, 'store'])->name('people.store');
    Route::get('/people/{person}/edit', [PersonController::class, 'edit'])->name('people.edit');
    Route::put('/people/{person}', [PersonController::class, 'update'])->name('people.update');
    Route::delete('/people/{person}', [PersonController::class, 'destroy'])->name('people.destroy');
    Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase');
    Route::post('/purchase', [PurchaseController::class, 'store'])->name('purchase.store');
    Route::get('/purchase/{id}/edit', [PurchaseController::class, 'edit'])->name('purchase.edit');
    Route::post('/purchase/{id}', [PurchaseController::class, 'update'])->name('purchase.update');
    Route::delete('/purchase/{id}', [PurchaseController::class, 'destroy'])->name('purchase.destroy');
    Route::get('/purchase/{id}/print', [PurchaseController::class, 'print'])->name('purchase.print');
    Route::get('/purchase/{id}/items', [PurchaseController::class, 'getItems'])->name('purchase.items');

    // Items (Inventory)
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
    Route::post('/items', [ItemController::class, 'store'])->name('items.store');
    
    // Dedicated Quick Add Page
    Route::get('/items/quick-add', [ItemController::class, 'quickAdd'])->name('items.quick-add');
    Route::post('/items/quick-add', [ItemController::class, 'storeQuick'])->name('items.store-quick');

    Route::post('/items/{item}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [ItemController::class, 'destroy'])->name('items.destroy');

    // Packing Routes
    Route::get('/packings', [PackingController::class, 'index'])->name('packings.index');
    Route::post('/packings', [PackingController::class, 'store'])->name('packings.store');
    Route::delete('/packings/{id}', [PackingController::class, 'destroy'])->name('packings.destroy');

    // Vendors
    Route::get('/vendors', [VendorsController::class, 'index'])->name('vendors.index');
    Route::get('/vendors/create', [VendorsController::class, 'create'])->name('vendors.create');
    Route::post('/vendors', [VendorsController::class, 'store'])->name('vendors.store');
    Route::get('/vendors/{id}/edit', [VendorsController::class, 'edit'])->name('vendors.edit');
    Route::post('/vendors/{id}', [VendorsController::class, 'update'])->name('vendors.update');
    Route::get('/vendors/{id}/delete', [VendorsController::class, 'destroy'])->name('vendors.destroy');

    // Sales
    Route::get('/sales', [SalesController::class, 'index'])->name('sales');
    Route::post('/sales', [SalesController::class, 'store'])->name('sales.store');
    Route::get('/sales/{id}/print', [SalesController::class, 'print'])->name('sales.print');
    Route::get('/sales/{id}/json', [SalesController::class, 'showJson'])->name('sales.json');
    Route::delete('/sales/{id}', [SalesController::class, 'destroy'])->name('sales.destroy');

    // Payment (ادائیگی) - بکری بُک
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');
    Route::post('/payment/navigate', [PaymentController::class, 'navigate'])->name('payment.navigate');
    Route::get('/payment/{id}', [PaymentController::class, 'show'])->name('payment.show')->where('id', '[0-9]+');
    Route::post('/payment/{id}', [PaymentController::class, 'update'])->name('payment.update')->where('id', '[0-9]+');

    // Labor Rates (مزدوری) - Sabzi Mandi
    Route::get('/labor-rates', [LaborRateController::class, 'index'])->name('labor-rates.index');
    Route::post('/labor-rates', [LaborRateController::class, 'store'])->name('labor-rates.store');
    Route::post('/labor-rates/{id}', [LaborRateController::class, 'update'])->name('labor-rates.update');
    Route::get('/labor-rates/by-code', [LaborRateController::class, 'getByCode'])->name('labor-rates.by-code');
    Route::get('/labor-rates/search', [LaborRateController::class, 'search'])->name('labor-rates.search');

    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/search-purchasers', [ReportsController::class, 'searchPurchasers'])->name('reports.search-purchasers');
    Route::get('/balance-sheet', [ReportsController::class, 'balanceSheet'])->name('balance-sheet');
    Route::post('/balance-sheet/expense', [ReportsController::class, 'addExpense'])->name('balance-sheet.expense.add');
    Route::post('/balance-sheet/income', [ReportsController::class, 'addIncome'])->name('balance-sheet.income.add');

    // Laga Details
    Route::get('/laga-details', [PurchaserDetailController::class, 'index'])->name('laga-details.index');
    Route::get('/laga-details/{id}', [PurchaserDetailController::class, 'show'])->name('laga-details.show');

    Route::get('/recovery', [RecoveryController::class, 'index'])->name('recovery');
    Route::post('/recovery', [RecoveryController::class, 'store'])->name('recovery.store');

    // Custom menu target (فو - Dues/Rokad)
    Route::get('/rokad', [RokadController::class, 'index'])->name('rokad');
    Route::post('/rokad/payment', [RokadController::class, 'storePayment'])->name('rokad.payment.store');
    Route::post('/rokad/advance', [RokadController::class, 'storeAdvance'])->name('rokad.advance.store');
    Route::get('/rokad/balance', [RokadController::class, 'balanceApi'])->name('rokad.balance');

    // Backup Routes
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup', [BackupController::class, 'store'])->name('backup.store');
    Route::get('/backup/{filename}', [BackupController::class, 'download'])->name('backup.download');
    Route::delete('/backup/{filename}', [BackupController::class, 'destroy'])->name('backup.destroy');
    Route::post('/row-backups/{backup}/restore', [RowBackupController::class, 'restore'])->name('row-backups.restore');

    Route::get('/stock', [StockController::class, 'index'])->name('stock');
    Route::get('/stock/bakery', [StockController::class, 'bakeryList'])->name('stock.bakery');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('/stock/updates', [StockController::class, 'updates'])->name('stock.updates');
    Route::get('/stock/bakery', [StockController::class, 'bakeryList'])->name('stock.bakery');
    Route::post('/stock/low-stock-alert', [StockController::class, 'toggleLowStockAlert'])->name('stock.low-stock-alert');
    Route::post('/stock/{id}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{id}', [StockController::class, 'destroy'])->name('stock.destroy');

    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('/inventory/{item}/adjust-stock', [InventoryController::class, 'adjustStock'])->name('inventory.adjust');
    Route::put('/inventory/{item}', [InventoryController::class, 'update'])->name('inventory.update');
    Route::get('/inventory/export', [InventoryController::class, 'export'])->name('inventory.export');

    // Bank/Cash Transactions
    Route::get('/bank-cash', [TransactionController::class, 'index'])->name('bank-cash');
    Route::post('/bank-cash', [TransactionController::class, 'store'])->name('bank-cash.store');

    Route::get('/transactions/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/api/language/switch', [SettingsController::class, 'switchLanguage'])->name('language.switch');
    Route::post('/api/settings/role', [SettingsController::class, 'updateRole'])->name('settings.role.update');

    // Profile routes (accessible to all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Role Management (Super Admin only)
    Route::get('/settings/roles', [RoleController::class, 'index'])->name('settings.roles');
    Route::post('/settings/roles', [RoleController::class, 'createRole'])->name('roles.create');
    Route::put('/settings/roles/{role}', [RoleController::class, 'updateRolePermissions'])->name('roles.update');
    Route::delete('/settings/roles/{role}', [RoleController::class, 'deleteRole'])->name('roles.delete');

    // User Management (Super Admin and Admin)
    Route::get('/settings/users', [RoleController::class, 'users'])->name('settings.users');
    Route::post('/settings/users', [RoleController::class, 'createUser'])->name('users.create');
    Route::put('/settings/users/{user}/role', [RoleController::class, 'updateUserRole'])->name('users.update-role');
    Route::put('/settings/users/{user}/permissions', [RoleController::class, 'updateUserPermissions'])->name('users.update-permissions');

    // Bakery Route
    Route::get('/bakery', [PaymentController::class, 'bakery'])->name('bakery');
    
    // Dual-Table Search (Owners + Purchasers)
    Route::get('/records/search', [PaymentController::class, 'searchRecords'])->name('records.search');
    Route::get('/records/live-search', [PaymentController::class, 'liveSearchRecords'])->name('records.live-search');
});
