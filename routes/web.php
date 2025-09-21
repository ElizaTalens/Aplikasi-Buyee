<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('login');
});
Route::get('/register', function () {
    return view('register');
});
// use App\Http\Controllers\RegisterController;

// Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
// Route::post('/register', [RegisterController::class, 'register'])->name('register');
