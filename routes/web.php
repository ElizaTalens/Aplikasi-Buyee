<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;



// -------------------------------------------------------------------------
// Rute Publik
// -------------------------------------------------------------------------

// Home - Rute utama Anda
Route::get('/', [HomeController::class, 'index'])->name('home');
// login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// admin
Route:: get ('/buyee_admin_dashboard', function () {
    return view('buyee_admin_dashboard');
})->middleware('auth')->name('admin.dashboard');

// user
Route::get('/user/home', function () {
    return redirect()->route('home');
})->name('user.dashboard')->middleware('auth');

// checkout
Route:: get ('/checkout', function () {
    return view('checkout');
});

// searchResult
Route:: get ('/SearchResult', function () {
    return view('SearchResult');
});

// Profile routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    
    // Main profile page
    Route::get('/user_profil', [ProfileController::class, 'index'])->name('user_profil')->middleware('auth');

    // Biodata management
    Route::get('/profile/biodata/edit', [ProfileController::class, 'editBiodata'])->name('profile.biodata.edit');
    Route::put('/profile/biodata', [ProfileController::class, 'updateBiodata'])->name('profile.biodata.update');
    
    // Contact management
    Route::get('/profile/contact/edit', [ProfileController::class, 'editContact'])->name('profile.contact.edit');
    Route::put('/profile/contact', [ProfileController::class, 'updateContact'])->name('profile.contact.update');
    
    // Profile photo
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');
    
    // Address management
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    
    // Payment methods
    Route::get('/profile/payment-methods', [ProfileController::class, 'paymentMethods'])->name('profile.payment-methods');
    
    // Transaction history
    Route::get('/profile/transactions', [ProfileController::class, 'transactions'])->name('transactions');
    
    // Notifications
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    
    // Wishlist
    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
    
    // Favorite stores
    Route::get('/profile/favorite-stores', [ProfileController::class, 'favoriteStores'])->name('profile.favorite-stores');
    
    // Settings
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    
});

// API routes untuk AJAX calls
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/profile/wallet-balance', [ProfileController::class, 'getWalletBalance']);
    Route::get('/profile/notifications/unread-count', [ProfileController::class, 'getUnreadNotificationsCount']);
    Route::get('/profile/cart/items-count', [ProfileController::class, 'getCartItemsCount']);
});

Route::prefix('buyee-admin')->group(function () {
    // Rute untuk Dashboard
    Route::get('/dashboard/stats', [AdminController::class, 'getDashboardStats']);

    // Rute untuk Produk
    Route::get('/products', [AdminController::class, 'getProducts']);
    Route::get('/products/{id}', [AdminController::class, 'getProduct']);
    Route::post('/products/save', [AdminController::class, 'saveProduct']);
    Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct']);

    // Rute untuk Kategori
    Route::get('/categories', [AdminController::class, 'getCategories']);
    Route::get('/categories/{id}', [AdminController::class, 'getCategory']);
    Route::post('/categories/save', [AdminController::class, 'saveCategory']);
    Route::delete('/categories/delete/{id}', [AdminController::class, 'deleteCategory']);

    // Rute untuk Pesanan
    Route::get('/orders', [AdminController::class, 'getOrders']);
    Route::post('/orders/update-status', [AdminController::class, 'updateOrderStatus']);
});
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/product/{id}', [CatalogController::class, 'show'])->name('product.detail');


