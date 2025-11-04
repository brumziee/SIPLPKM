<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;

// ===== Import Controller Baru =====
use App\Http\Controllers\PemilikController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\RewardController;
use App\Http\Controllers\PoinLoyalitasController;
use App\Http\Controllers\PenukaranPoinController;
// ==================================

use Illuminate\Support\Facades\Route;

// Route utama
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : view('auth.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Middleware auth
Route::middleware('auth')->group(function () {

    // ===== Profile =====
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===== Notifications =====
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::post('/mark-as-read', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::get('/count', [NotificationController::class, 'getCount'])->name('count');
    });

    // ===== Sistem Loyalitas =====
    // Pelanggan routes
    Route::middleware('permission:pelanggan.view')->prefix('pelanggan')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('/create', [PelangganController::class, 'create'])
            ->middleware('permission:pelanggan.create')
            ->name('pelanggan.create');
        Route::post('/', [PelangganController::class, 'store'])
            ->middleware('permission:pelanggan.store')
            ->name('pelanggan.store');
        Route::get('/{id}', [PelangganController::class, 'show'])->name('pelanggan.show');
        Route::get('/{id}/edit', [PelangganController::class, 'edit'])
            ->middleware('permission:pelanggan.edit')
            ->name('pelanggan.edit');
        Route::put('/{id}', [PelangganController::class, 'update'])
            ->middleware('permission:pelanggan.update')
            ->name('pelanggan.update');
        Route::delete('/{id}', [PelangganController::class, 'destroy'])
            ->middleware('permission:pelanggan.delete')
            ->name('pelanggan.destroy');

        // Form penukaran poin global
        Route::get('/tukar-poin', [PelangganController::class, 'tukarPoinFormGlobal'])
            ->middleware('permission:penukaran.view')
            ->name('pelanggan.tukar-poin');

        // Tambah poin manual
        Route::post('/{id}/tambah-poin', [PelangganController::class, 'tambahPoin'])
            ->middleware('permission:poin.add')
            ->name('pelanggan.tambah-poin');
    });

    // ===== Reward routes =====
    Route::prefix('reward')->group(function () {
        Route::get('/', [RewardController::class, 'index'])->name('reward.index');
        Route::get('/create', [RewardController::class, 'create'])->name('reward.create');
        Route::post('/', [RewardController::class, 'store'])->name('reward.store');
        Route::get('/{id}', [RewardController::class, 'show'])->name('reward.show');
        Route::get('/{id}/edit', [RewardController::class, 'edit'])->name('reward.edit');
        Route::put('/{id}', [RewardController::class, 'update'])->name('reward.update');
        Route::delete('/{id}', [RewardController::class, 'destroy'])->name('reward.destroy');
    });

    // ===== Penukaran Poin routes =====
    Route::prefix('penukaran-poin')->middleware(['auth'])->group(function () {
        Route::get('/', [PenukaranPoinController::class, 'index'])->name('penukaran-poin.index');
        Route::get('/create', [PenukaranPoinController::class, 'create'])->name('penukaran-poin.create')->middleware('permission:penukaran-poin.create');
        Route::post('/store', [PenukaranPoinController::class, 'store'])->name('penukaran-poin.store')->middleware('permission:penukaran-poin.create');
        Route::get('/{id}/edit', [PenukaranPoinController::class, 'edit'])->name('penukaran-poin.edit')->middleware('permission:penukaran-poin.update');
        Route::put('/{id}', [PenukaranPoinController::class, 'update'])->name('penukaran-poin.update')->middleware('permission:penukaran-poin.update');
        Route::delete('/{id}', [PenukaranPoinController::class, 'destroy'])->name('penukaran-poin.destroy')->middleware('permission:penukaran-poin.delete');
    });

    // ===== Categories =====
    Route::middleware('permission:category.view')->group(function () {
        Route::get('/kategori', [CategoryController::class, 'index'])->name('category.index');
        Route::post('/kategori', [CategoryController::class, 'store'])
            ->middleware('permission:category.store')
            ->name('category.store');
        Route::put('/kategori/{id}', [CategoryController::class, 'update'])
            ->middleware('permission:category.update')
            ->name('category.update');
        Route::delete('/kategori/{id}', [CategoryController::class, 'destroy'])
            ->middleware('permission:category.delete')
            ->name('category.destroy');
    });

    // ===== Suppliers =====
    Route::middleware('permission:supplier.view')->group(function () {
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::post('/supplier', [SupplierController::class, 'store'])
            ->middleware('permission:supplier.store')
            ->name('supplier.store');
        Route::put('/supplier/{id}', [SupplierController::class, 'update'])
            ->middleware('permission:supplier.update')
            ->name('supplier.update');
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])
            ->middleware('permission:supplier.delete')
            ->name('supplier.destroy');
    });

    // ===== Units =====
    Route::middleware('permission:unit.view')->group(function () {
        Route::get('/satuan', [UnitController::class, 'index'])->name('unit.index');
        Route::post('/satuan', [UnitController::class, 'store'])
            ->middleware('permission:unit.store')
            ->name('unit.store');
        Route::put('/satuan/{id}', [UnitController::class, 'update'])
            ->middleware('permission:unit.update')
            ->name('unit.update');
        Route::delete('/satuan/{id}', [UnitController::class, 'destroy'])
            ->middleware('permission:unit.delete')
            ->name('unit.destroy');
    });

    // ===== Users =====
    Route::prefix('user')->group(function () {
        Route::middleware('permission:user.view')->get('/', [UserController::class, 'index'])->name('user.index');
        Route::middleware('permission:user.store')->post('/', [UserController::class, 'store'])->name('user.store');
        Route::middleware('permission:user.update')->put('/{user}', [UserController::class, 'update'])->name('user.update');
        Route::middleware('permission:user.delete')->delete('/{user}', [UserController::class, 'destroy'])->name('user.destroy');
    });

    // ===== Roles =====
    Route::prefix('role')->group(function () {
        Route::middleware('permission:role.view')->get('/', [RoleController::class, 'index'])->name('role.index');
        Route::middleware('permission:role.update')->get('/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
        Route::middleware('permission:role.update')->put('/{role}', [RoleController::class, 'update'])->name('role.update');
    });

    // ===== Products =====
    Route::middleware('permission:product.view')->group(function () {
        Route::get('/produk', [ProductController::class, 'index'])->name('product.index');
        Route::get('/produk/create', [ProductController::class, 'create'])
            ->middleware('permission:product.create')
            ->name('product.create');
        Route::post('/produk', [ProductController::class, 'store'])
            ->middleware('permission:product.store')
            ->name('product.store');
        Route::get('/produk/{id}', [ProductController::class, 'show'])->name('product.show');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])
            ->middleware('permission:product.edit')
            ->name('product.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])
            ->middleware('permission:product.update')
            ->name('product.update');
        Route::delete('/produk/{id}', [ProductController::class, 'destroy'])
            ->middleware('permission:product.delete')
            ->name('product.destroy');
    });

    // ===== Stock Management =====
    Route::middleware('permission:stock.view')->prefix('stok')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('stock.index');
        Route::get('/riwayat', [StockController::class, 'movements'])->name('stock.movements');
        Route::get('/masuk', [StockController::class, 'stockInForm'])->middleware('permission:stock.in')->name('stock.in.form');
        Route::post('/masuk', [StockController::class, 'stockIn'])->middleware('permission:stock.in')->name('stock.in');
        Route::get('/keluar', [StockController::class, 'stockOutForm'])->middleware('permission:stock.out')->name('stock.out.form');
        Route::post('/keluar', [StockController::class, 'stockOut'])->middleware('permission:stock.out')->name('stock.out');
        Route::get('/penyesuaian', [StockController::class, 'adjustmentForm'])->middleware('permission:stock.adjustment')->name('stock.adjustment.form');
        Route::post('/penyesuaian', [StockController::class, 'adjustment'])->middleware('permission:stock.adjustment')->name('stock.adjustment');
    });

    // ===== Sales =====
    Route::middleware('auth')->prefix('penjualan')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('sales.pos');
        Route::get('/riwayat', [SalesController::class, 'history'])->name('sales.history');
        Route::get('/{id}', [SalesController::class, 'show'])->name('sales.show');
        Route::get('/{id}/struk', [SalesController::class, 'receipt'])->name('sales.receipt');
        Route::get('/{id}/cetak', [SalesController::class, 'printReceipt'])->name('sales.print');
        Route::post('/{id}/batal', [SalesController::class, 'cancel'])->name('sales.cancel');

        // Cart
        Route::prefix('keranjang')->group(function () {
            Route::post('/tambah', [SalesController::class, 'addToCart'])->name('sales.cart.add');
            Route::put('/update', [SalesController::class, 'updateCart'])->name('sales.cart.update');
            Route::delete('/hapus', [SalesController::class, 'removeFromCart'])->name('sales.cart.remove');
            Route::get('/data', [SalesController::class, 'getCart'])->name('sales.cart.get');
            Route::delete('/kosongkan', [SalesController::class, 'clearCart'])->name('sales.cart.clear');
        });

        Route::post('/checkout', [SalesController::class, 'checkout'])->name('sales.checkout');
        Route::get('/ringkasan-hari-ini', [SalesController::class, 'todaySummary'])->name('sales.today');
    });

    // ===== Purchases =====
    Route::middleware('permission:purchase.view')->prefix('pembelian')->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('purchases.index');
        Route::get('/create', [PurchaseController::class, 'create'])->middleware('permission:purchase.create')->name('purchases.create');
        Route::post('/', [PurchaseController::class, 'store'])->middleware('permission:purchase.store')->name('purchases.store');
        Route::get('/{id}', [PurchaseController::class, 'show'])->middleware('permission:purchase.show')->name('purchases.show');
        Route::delete('/{id}', [PurchaseController::class, 'destroy'])->middleware('permission:purchase.delete')->name('purchases.destroy');
    });

    // ===== Settings =====
    Route::prefix('pengaturan')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/', [SettingController::class, 'update'])->name('settings.update');
    });
});

require __DIR__ . '/auth.php';
