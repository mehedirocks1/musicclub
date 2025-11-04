<?php
use Modules\Members\Http\Controllers\MembersController;
use App\Http\Controllers\MemberAuthController;
use Illuminate\Support\Facades\Route;

// Member authentication (public)
Route::prefix('member')->group(function () {
    Route::get('/login', [MemberAuthController::class, 'showLoginForm'])->name('member.login');
    Route::post('/login', [MemberAuthController::class, 'login']);
    Route::post('/logout', [MemberAuthController::class, 'logout'])->name('member.logout');
});

// Member dashboard & profile (member guard)
Route::middleware(['auth:member'])->prefix('member')->name('member.')->group(function () {
    Route::get('/dashboard', [MembersController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [MembersController::class, 'profile'])->name('profile');
    Route::post('/profile', [MembersController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [MembersController::class, 'changePassword'])->name('change-password');
    Route::post('/change-password', [MembersController::class, 'updatePassword'])->name('change-password.update');
    Route::get('/pay-fee', [MembersController::class, 'payFee'])->name('pay-fee');
    Route::get('/check-payments', [MembersController::class, 'checkPayments'])->name('check-payments');

    // Optional: allow members to export their own data
    Route::get('/export', [MembersController::class, 'export'])->name('export');
});

// Admin export (web guard)

// Member ID card
Route::get('/members/{id}/card', [MembersController::class, 'memberCard'])->name('members.card');


Route::middleware(['auth:web'])->prefix('admin')->name('members.')->group(function () {
    Route::get('/export-members', [\Modules\Members\Http\Controllers\MembersController::class, 'export'])->name('export');
});
