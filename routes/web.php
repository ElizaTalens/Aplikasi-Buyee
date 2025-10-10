<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda mendaftarkan semua rute untuk aplikasi Anda.
|
*/

/**
 * Route untuk Halaman Utama (Homepage)
 * Biasanya menampilkan halaman selamat datang atau halaman utama produk.
 */
Route::get('/', function () {
    // Mengarahkan ke view 'welcome.blade.php' atau 'beranda.blade.php'
    return view('checkout'); 
});

/**
 * Route untuk Halaman Hasil Pencarian
 * Mengarah ke fungsi 'index' di dalam SearchController.
 */
Route::get('/pencarian', [SearchController::class, 'index'])->name('search.result');

/**
 * Route untuk Halaman Status Pesanan Pengguna
 * Mengarah ke fungsi 'index' di dalam OrderController.
 */
Route::get('/pesanan-saya', [OrderController::class, 'index'])->name('orders.index');
Route::resource('products', ProductController::class);
// Route ini akan menangani URL seperti http://127.0.0.1:8000/search
Route::get('/search', [SearchController::class, 'index'])->name('search');