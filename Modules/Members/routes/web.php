<?php

use Illuminate\Support\Facades\Route;
use Modules\Members\Http\Controllers\MembersController;
use App\Http\Controllers\MemberAuthController;

/*
|--------------------------------------------------------------------------
| Member Authentication Routes
|--------------------------------------------------------------------------
|
| These routes handle login, logout for members.
| No auth middleware applied here because these are public forms.
|
*/

/*

|--------------------------------------------------------------------------

| Member Authentication Routes

|--------------------------------------------------------------------------

*/

Route::prefix('member')->group(function () {
    // This named route is correct
    Route::get('/login', [MemberAuthController::class, 'showLoginForm'])->name('member.login');
    Route::post('/login', [MemberAuthController::class, 'login']);
    // This named route is also correct
    Route::post('/logout', [MemberAuthController::class, 'logout'])->name('member.logout');

});

/*
|--------------------------------------------------------------------------
| Member Authenticated Routes
|--------------------------------------------------------------------------
|
| Routes protected by the 'member' guard
|
*/
Route::middleware(['auth:member'])->prefix('member')->group(function () {
    Route::resource('members', MembersController::class)->names('members');
});






Route::prefix('member')->middleware('auth:member')->name('member.')->group(function () {
    Route::get('/dashboard', [MembersController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [MembersController::class, 'profile'])->name('profile');
    Route::post('/profile', [MembersController::class, 'updateProfile'])->name('profile.update');

    Route::get('/change-password', [MembersController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [MembersController::class, 'updatePassword'])->name('change-password.update');

    Route::get('/pay-fee', [MembersController::class, 'payFee'])->name('pay-fee');
    Route::get('/check-payments', [MembersController::class, 'checkPayments'])->name('check-payments');
});
