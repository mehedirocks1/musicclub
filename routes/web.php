<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\SslcommerzController; 
use \Raziul\Sslcommerz\Facades\Sslcommerz;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use DevWizard\Textify\Facades\Textify;   // ADD ONLY
use Illuminate\Support\Str; 
use Modules\Packages\Models\Package;
use App\Http\Controllers\AboutController;


Route::view('/', 'Frontend.home')->name('home');
Route::view('/about', 'Frontend.about')->name('about');
Route::view('/branch', 'Frontend.branch')->name('branch');
Route::view('/contact', 'Frontend.contact')->name('contact');
Route::view('/gallery', 'Frontend.gallery')->name('gallery');


Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Use a dedicated controller for handling callbacks





// Public about page
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Admin routes (example group - protect with auth + permission middleware)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('about/edit', [AboutController::class, 'edit'])->name('about.edit');
    Route::post('about', [AboutController::class, 'update'])->name('about.update');
});




Route::match(['GET','POST'],'sslcommerz/init',[SslcommerzController::class,'init'])->name('sslc.init');
Route::match(['GET','POST'],'sslcommerz/success',[SslcommerzController::class,'success'])->name('sslc.success')->withoutMiddleware([VerifyCsrfToken::class]);
Route::match(['GET','POST'],'sslcommerz/failure',[SslcommerzController::class,'failure'])->name('sslc.failure')->withoutMiddleware([VerifyCsrfToken::class]);
Route::match(['GET','POST'],'sslcommerz/cancel',[SslcommerzController::class,'cancel'])->name('sslc.cancel')->withoutMiddleware([VerifyCsrfToken::class]);
Route::post('sslcommerz/ipn',[SslcommerzController::class,'ipn'])->name('sslc.ipn')->withoutMiddleware([VerifyCsrfToken::class]);

