<?php

use Illuminate\Support\Facades\Route;
use Modules\Packages\Http\Controllers\PackagesController;
use App\Http\Controllers\SslCommerzPaymentController;
// ✅ public routes for Packages module
Route::prefix('packages')->name('frontend.packages.')->group(function () {
    Route::get('/', [PackagesController::class, 'index'])->name('index');
    Route::get('/{package:slug}', [PackagesController::class, 'show'])->name('show');

    // ➜ ফর্ম দেখানোর GET রুট
    Route::get('/{package:slug}/buy', [PackagesController::class, 'buyForm'])->name('buy.form');

    // ➜ ফর্ম সাবমিটের POST রুট (আপনারটা আগেই ছিল)
    Route::post('/{package:slug}/buy', [PackagesController::class, 'buy'])->name('buy');
});