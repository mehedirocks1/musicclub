<?php

use Illuminate\Support\Facades\Route;
use Modules\Subscribers\Http\Controllers\SubscribersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('subscribers', SubscribersController::class)->names('subscribers');
});
