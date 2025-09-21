<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/login', function () {
//     return view('login');
// });
use App\Http\Controllers\LoginController;

// tampilkan form login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');

// proses login
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// Route::get('/register', function () {
//     return view('register');
// });
use App\Http\Controllers\RegisterController;

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
