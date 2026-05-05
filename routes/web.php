<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\VendorMenuController;
use App\Http\Controllers\VendorPesananController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
Route::get('/api/vendors', [CustomerController::class, 'getVendors']);
Route::get('/api/menu/{idvendor}', [CustomerController::class, 'getMenuByVendor']);
Route::post('/api/pesan', [CustomerController::class, 'store']);
Route::get('/payment/{idpesanan}', [CustomerController::class, 'payment'])->name('customer.payment');
Route::get('/success/{idpesanan}', [CustomerController::class, 'success'])->name('customer.success');

/*
|--------------------------------------------------------------------------
| Vendor Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/vendor/login', [VendorAuthController::class, 'showLogin'])->name('vendor.login');
Route::post('/vendor/login', [VendorAuthController::class, 'login'])->name('vendor.login.process');
Route::post('/vendor/logout', [VendorAuthController::class, 'logout'])->name('vendor.logout');

/*
|--------------------------------------------------------------------------
| Vendor Dashboard Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::prefix('vendor')->middleware('vendor.auth')->group(function () {
    // Menu CRUD
    Route::get('/menu', [VendorMenuController::class, 'index'])->name('vendor.menu.index');
    Route::post('/menu', [VendorMenuController::class, 'store'])->name('vendor.menu.store');
    Route::put('/menu/{id}', [VendorMenuController::class, 'update'])->name('vendor.menu.update');
    Route::delete('/menu/{id}', [VendorMenuController::class, 'destroy'])->name('vendor.menu.destroy');

    // Pesanan
    Route::get('/pesanan', [VendorPesananController::class, 'index'])->name('vendor.pesanan.index');
    Route::get('/pesanan/scan', [VendorPesananController::class, 'scan'])->name('vendor.pesanan.scan');
    Route::get('/pesanan/{id}', [VendorPesananController::class, 'show'])->name('vendor.pesanan.show');
});

/*
|--------------------------------------------------------------------------
| Payment Routes
|--------------------------------------------------------------------------
*/

// Midtrans Webhook (exclude from CSRF)
Route::post('/midtrans/callback', [PaymentController::class, 'callback'])->name('midtrans.callback');

// Manual payment confirmation (for testing)
Route::post('/payment/manual/{idpesanan}', [PaymentController::class, 'manualConfirm'])->name('payment.manual');
