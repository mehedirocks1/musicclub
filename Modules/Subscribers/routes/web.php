<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscribers\Http\Controllers\SubscribersController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('subscribers', SubscribersController::class)->names('subscribers');
});
