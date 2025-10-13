<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::view('/', 'Frontend.home')->name('home');
Route::view('/about', 'Frontend.about')->name('about');
Route::view('/branch', 'Frontend.branch')->name('branch');
Route::view('/contact', 'Frontend.contact')->name('contact');
Route::view('/gallery', 'Frontend.gallery')->name('gallery');


Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');