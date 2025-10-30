<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\RestockController;
use App\Http\Controllers\BiayaOperasionalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LoginController::class, 'index']);
Route::get('/loginadmin', [LoginController::class, 'adminLogin']);
Route::get('/error-unauthorized', [LoginController::class, 'errorUnauthorized']);
Route::get('/error-admin-only', [LoginController::class, 'errorAdminOnly']);
Route::post('/login', [LoginController::class, 'authenticate'])->name('login');
Route::post('/loginadmin', [LoginController::class, 'authenticateAdmin'])->name('loginadmin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Captcha routes
Route::post('/store-math-captcha', [LoginController::class, 'storeMathCaptcha']);
Route::post('/store-captcha', [LoginController::class, 'storeCaptcha']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth');
Route::get('/dashboard/cashier/quick-transaction', [CashierController::class, 'quickTransaction'])->middleware('auth');

Route::middleware(['auth', 'kasir-or-admin'])->group(function () {
    // Categories - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    Route::delete('/dashboard/categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
    Route::resource('/dashboard/categories', CategoryController::class);
});

Route::middleware(['auth', 'admin-or-manager'])->group(function () {
    // Users - ADMIN dan MANAJER bisa akses
    Route::delete('/dashboard/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulkDelete');
    Route::resource('/dashboard/users', UserController::class);

    // Transactions - ADMIN dan MANAJER bisa akses
    Route::delete('/dashboard/transactions/bulk-delete', [TransactionController::class, 'bulkDelete'])->name('transactions.bulkDelete');
    Route::resource('/dashboard/transactions', TransactionController::class);

    // ORDER BY NOTA - ADMIN dan MANAJER bisa akses
    Route::match(['get', 'post'], '/dashboard/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::post('/dashboard/orders/create', [OrderController::class, 'create']);
    Route::post('/dashboard/orders/store', [OrderController::class, 'store']);
    Route::post('/dashboard/transactions/checkout', [OrderController::class, 'checkout']);
    Route::delete('/dashboard/orders/{order}', [OrderController::class, 'destroy']);
});

// KASIR ROUTES - Hanya untuk KASIR dan ADMIN (MANAJER tidak bisa akses)
Route::middleware(['auth', 'kasir-or-admin'])->group(function () {
    Route::get('/dashboard/cashier', [CashierController::class, 'index']);
    Route::get('/dashboard/cashier/create', [CashierController::class, 'createtransaction']);
    Route::post('/dashboard/cashier/create', [CashierController::class, 'storetransaction']);
    Route::get('/dashboard/cashier/createorder', [CashierController::class, 'createorder']);
    Route::post('/dashboard/cashier/createorder', [CashierController::class, 'createorder']);
    Route::post('/dashboard/cashier/storeorder', [CashierController::class, 'storeorder']);
    Route::post('/dashboard/cashier/store-barcode', [CashierController::class, 'storeOrderFromBarcode'])->name('cashier.storeBarcode');
    Route::put('/dashboard/cashier/update-qty/{order}', [CashierController::class, 'updateOrderQty'])->name('cashier.updateQty');
    Route::delete('/dashboard/cashier/deleteorder/{order}', [CashierController::class, 'deleteorder'])->name('cashier.deleteOrder');
    Route::post('/dashboard/cashiers/checkout', [CashierController::class, 'checkout']);
    Route::post('/dashboard/cashiers/finishing', [CashierController::class, 'finishing']);
    Route::get('/dashboard/cashier/search-goods', [CashierController::class, 'searchGoods'])->name('cashier.searchGoods');
});

Route::middleware(['auth', 'pdf'])->group(function () {
    Route::post('/dashboard/cashiers/nota', [CashierController::class, 'nota']);
    Route::get('/dashboard/cashiers/print-nota', [CashierController::class, 'printNota'])->name('cashier.printNota');
});

Route::middleware(['auth', 'kasir-or-admin'])->group(function () {
    // Biaya Operasional - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    Route::delete('/dashboard/biayaoperasional/bulk-delete', [BiayaOperasionalController::class, 'bulkDelete'])->name('biayaoperasional.bulkDelete');
    Route::resource('/dashboard/biayaoperasional', BiayaOperasionalController::class);

    // Goods - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    Route::get('/dashboard/goods/cetakbarcode', [GoodController::class, 'showPrintMultipleBarcodesForm'])->name('goods.cetakbarcode.form');
    Route::post('/dashboard/goods/cetakbarcode', [GoodController::class, 'generateMultipleBarcodesPdf'])->name('goods.cetakbarcode.pdf');
    Route::delete('/dashboard/goods/bulk-delete', [GoodController::class, 'bulkDelete'])->name('goods.bulkDelete');
    Route::resource('/dashboard/goods', GoodController::class);
    Route::post('/dashboard/goods/{good}/generate-barcode', [GoodController::class, 'generateBarcode']);
    Route::get('/dashboard/goods/{good}/download-barcode', [GoodController::class, 'downloadBarcode']);
    Route::get('/dashboard/goods/{good}/print-barcode', [GoodController::class, 'printBarcode']);
    Route::post('/dashboard/restock/update', [RestockController::class, 'updateStock'])->name('restock.updateStock');

    // Customers - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    // Route::resource('/dashboard/customers', CustomerController::class);

    // Returns - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    Route::delete('/dashboard/returns/bulk-delete', [ReturnController::class, 'bulkDelete'])->name('returns.bulkDelete');
    Route::get('/dashboard/returns/search-goods', [ReturnController::class, 'searchGoods'])->name('returns.searchGoods');
    Route::resource('/dashboard/returns', ReturnController::class);

    // RESTOCK ROUTES - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/restock', [RestockController::class, 'index'])->name('restock.index');
    Route::get('/restock/baru', [RestockController::class, 'restockBaru'])->name('restock.baru');
    Route::post('/restock/eksisting', [RestockController::class, 'restockEksisting'])->name('restock.eksisting');
    Route::post('/restock/store', [RestockController::class, 'storeRestock'])->name('restock.store');
    Route::post('/restock/update', [RestockController::class, 'updateStock'])->name('restock.updateStock');
    Route::get('/restock/{restock}/edit-restock', [RestockController::class, 'editRestock'])->name('restock.editRestock');
    Route::put('/restock/{restock}/update', [RestockController::class, 'updateRestock'])->name('restock.updateRestock');
    Route::get('/restock/{good}/edit', [RestockController::class, 'edit'])->name('restock.edit');
    Route::put('/restock/{good}', [RestockController::class, 'update'])->name('restock.update');
    Route::delete('/restock/{restock}', [RestockController::class, 'destroy'])->name('restock.destroy');
    Route::delete('/restock/bulk-delete', [RestockController::class, 'bulkDelete'])->name('restock.bulkDelete');


    // PRINT ROUTES - KASIR dan ADMIN bisa akses (Manajer tidak bisa akses)
    Route::get('/dashboard/goods/print-all', [GoodController::class, 'printAll']);
    Route::get('/dashboard/returns/print-all', [ReturnController::class, 'printAll']);
});

Route::middleware('auth')->group(function () {
    // REKAPITULASI - Semua role bisa akses
    Route::get('/dashboard/rekapitulasi', [ReportController::class, 'index']);
    Route::post('/dashboard/rekapitulasi/cetak-transaksi', [ReportController::class, 'cetakTransaksi']);
    Route::post('/dashboard/rekapitulasi/cetak-barang', [ReportController::class, 'cetakBarang']);
    Route::post('/dashboard/rekapitulasi/cetak-restock-return', [ReportController::class, 'cetakRestockReturn']);
    Route::post('/dashboard/rekapitulasi/cetak-biaya-operasional', [ReportController::class, 'cetakBiayaOperasional'])->name('reports.cetakBiayaOperasional');
    Route::post('/dashboard/rekapitulasi/cetak-laporan-keuangan', [ReportController::class, 'cetakLaporanKeuangan'])->name('reports.cetakLaporanKeuangan');
});});