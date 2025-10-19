<?php

use Illuminate\Support\Facades\Route;
use Modules\Packages\Http\Controllers\PackagesController;

Route::prefix('packages')->name('packages.')->group(function () {
    Route::get('/', [PackageController::class, 'index'])->name('index');
    Route::get('/{slug}', [PackageController::class, 'show'])->name('show');
});