<?php

use Illuminate\Support\Facades\Route;
use Modules\Members\Http\Controllers\MembersController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('members', MembersController::class)->names('members');
});
