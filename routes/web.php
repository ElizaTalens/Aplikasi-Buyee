<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controllers
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController; 

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// FIX: Ganti rute Catalog menjadi terproteksi (jika belum login, arahkan ke login.form)
Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
Route::get('/product/{slug}', fn (string $slug) => view('pages.product-detail', compact('slug')))
    ->name('product.detail');

/*
|--------------------------------------------------------------------------
| AUTH: LOGIN / REGISTER / LOGOUT
|--------------------------------------------------------------------------
*/
// Rute Login dan Register tidak menggunakan middleware 'auth' agar bisa diakses
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// FIX: Logout harus menggunakan POST
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PRODUCT & CATEGORY (opsional jika dipakai)
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
Route::get('/categories/{id}', [CategoryController::class, 'show'])->name('categories.show');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');
/*
|--------------------------------------------------------------------------
| CART & WISHLIST (PROTECTED)
|--------------------------------------------------------------------------
| - Halaman cart & wishlist dibatasi login
*/
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
});

// Endpoint AJAX untuk menambah item ke session (tidak perlu middleware 'auth' disini jika hanya pakai session)
Route::post('/wishlist/add', function (Request $r) {
    $item = $r->validate(['sku' => 'required', 'name' => 'required', 'price' => 'numeric']);
    $wishlist = session('wishlist', []);
    if (!collect($wishlist)->firstWhere('sku', $item['sku'])) {
        $wishlist[] = $item;
    }
    session(['wishlist' => $wishlist]);
    return response()->json(['ok' => true, 'count' => count($wishlist)]);
})->name('wishlist.add');

Route::post('/cart/add', function (Request $r) {
    $item = $r->validate(['sku' => 'required', 'name' => 'required', 'price' => 'numeric']);
    $cart = session('cart', []);
    if (!collect($cart)->firstWhere('sku', $item['sku'])) {
        $cart[] = $item + ['qty' => 1];
    }
    session(['cart' => $cart]);
    return response()->json(['ok' => true, 'count' => count($cart)]);
})->name('cart.add');

/*
|--------------------------------------------------------------------------
| DASHBOARD & PAGES LAIN
|--------------------------------------------------------------------------
*/
// ADMIN DASHBOARD (beri name)
Route::middleware('auth')->group(function () {
    Route::get('/buyee_admin_dashboard', fn () => view('pages.buyee_admin_dashboard'))
        ->name('admin.dashboard');
});

Route::view('/checkout', 'pages.checkout')->name('checkout');
Route::view('/SearchResult', 'pages.SearchResult')->name('search.result');

/*
|--------------------------------------------------------------------------
| PROFILE (protected) - Rute Lengkap
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    // Rute Profil Utama
    Route::get('/user_profil', [ProfileController::class, 'index'])->name('profile.index');

    // Biodata
    Route::get('/profile/biodata/edit', [ProfileController::class, 'editBiodata'])->name('profile.biodata.edit');
    Route::put('/profile/biodata', [ProfileController::class, 'updateBiodata'])->name('profile.biodata.update');

    // Kontak
    Route::get('/profile/contact/edit', [ProfileController::class, 'editContact'])->name('profile.contact.edit');
    Route::put('/profile/contact', [ProfileController::class, 'updateContact'])->name('profile.contact.update');

    // Foto
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    // Transaksi (Diperlukan oleh Navbar)
    Route::get('/profile/transactions', [ProfileController::class, 'transactions'])->name('transactions');

    // Rute Profil Lainnya (Sesuai Controller Asli)
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::get('/profile/payment-methods', [ProfileController::class, 'paymentMethods'])->name('profile.payment-methods');
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist');
    Route::get('/profile/favorite-stores', [ProfileController::class, 'favoriteStores'])->name('profile.favorite-stores');

    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
});

/*
|--------------------------------------------------------------------------
| API KECIL (protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/profile/wallet-balance', [ProfileController::class, 'getWalletBalance']);
    Route::get('/profile/notifications/unread-count', [ProfileController::class, 'getUnreadNotificationsCount']);
    Route::get('/profile/cart/items-count', [ProfileController::class, 'getCartItemsCount']);
});

/*
|--------------------------------------------------------------------------
| ADMIN AREA (prefix: buyee-admin)
|--------------------------------------------------------------------------
*/
Route::prefix('buyee-admin')->group(function () {
    Route::get('/dashboard/stats', [AdminController::class, 'getDashboardStats']);

    Route::get('/products', [AdminController::class, 'getProducts']);
    Route::get('/products/{id}', [AdminController::class, 'getProduct']);
    Route::post('/products/save', [AdminController::class, 'saveProduct']);
    Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct']);

    Route::get('/categories', [AdminController::class, 'getCategories']);
    Route::get('/categories/{id}', [AdminController::class, 'getCategory']);
    Route::post('/categories/save', [AdminController::class, 'saveCategory']);
    Route::delete('/categories/delete/{id}', [AdminController::class, 'deleteCategory']);

    Route::get('/orders', [AdminController::class, 'getOrders']);
    Route::post('/orders/update-status', [AdminController::class, 'updateOrderStatus']);
});
