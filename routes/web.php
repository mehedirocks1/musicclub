<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\Auth\LoginController;


Route::view('/', 'Frontend.home')->name('home');
Route::view('/about', 'Frontend.about')->name('about');
Route::view('/branch', 'Frontend.branch')->name('branch');
Route::view('/contact', 'Frontend.contact')->name('contact');
Route::view('/gallery', 'Frontend.gallery')->name('gallery');


Route::get('/register', [RegistrationController::class, 'create'])->name('register');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
