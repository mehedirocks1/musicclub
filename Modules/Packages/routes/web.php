<?php

use Illuminate\Support\Facades\Route;
use Modules\Packages\Http\Controllers\PackagesController;

// ✅ public routes for Packages module
Route::prefix('packages')->name('frontend.packages.')->group(function () {
    Route::get('/', [PackagesController::class, 'index'])->name('index');
    Route::get('/{package:slug}', [PackagesController::class, 'show'])->name('show');
    Route::post('/{package:slug}/buy', [PackagesController::class, 'buy'])->name('buy');
});
