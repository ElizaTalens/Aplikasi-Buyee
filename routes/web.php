<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController; // REKOMENDASI: Bisa dibuat untuk detail
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');

// REKOMENDASI: Gunakan controller untuk detail produk agar lebih rapi
Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.detail');

// --- ORDERS (RIWAYAT PESANAN) ---  <-- TAMBAHKAN BAGIAN INI
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

/*
|--------------------------------------------------------------------------
| AUTH: LOGIN / REGISTER / LOGOUT
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

/*
|--------------------------------------------------------------------------
| PROTECTED PAGES (Cart, Wishlist, Profile, etc)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // --- CART ---
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    // FIX: Arahkan 'add to cart' ke controller, bukan session
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    // Tambahkan rute lain jika perlu (update, delete)
    // Route::patch('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    // Route::delete('/cart/{item}', [CartController::class, 'destroy'])->name('cart.destroy');

    // --- WISHLIST ---
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    // FIX: Arahkan 'add to wishlist' ke controller yang sudah kita buat
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');

    // --- PROFILE ---
    // FIX: Sederhanakan semua rute profil menjadi 2 rute ini yang sesuai dengan controller
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // --- CHECKOUT ---
 
    // Ganti route checkout ini
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

    // --- ADMIN DASHBOARD (jika diperlukan) ---
    Route::get('/buyee_admin_dashboard', fn () => view('pages.buyee_admin_dashboard'))
        ->name('admin.dashboard');
});