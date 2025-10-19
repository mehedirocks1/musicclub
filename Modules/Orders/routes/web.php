<?php

use Illuminate\Support\Facades\Route;
use Modules\Orders\Http\Controllers\OrdersController;
use Modules\Orders\Http\Controllers\CheckoutController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('orders', OrdersController::class)->names('orders');
});


// Checkout (subscriber login required)
Route::middleware('web')->group(function () {
    Route::post('/checkout/init', [CheckoutController::class, 'init'])->name('orders.init')
        ->middleware('auth:subscriber'); // সাবস্ক্রাইবার লগইন বাধ্যতামূলক

    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('orders.success');

    // SSLCommerz IPN (POST)
    Route::post('/checkout/ipn', [CheckoutController::class, 'ipn'])->name('orders.ipn');
});