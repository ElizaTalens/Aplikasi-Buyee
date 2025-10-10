<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// ==== Controllers dari branch "login" ====
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CatalogController;

// =====================================================================================
// HOME / LANDING (ambil versi halaman-home)
Route::get('/', fn () => view('pages.home'))->name('home');
// Jika belum punya view 'pages.home', ganti ke: view('welcome')



// -------------------------------------------------------------------------
// Rute Publik
// -------------------------------------------------------------------------

// Home - Rute utama Anda
Route::get('/', [HomeController::class, 'index'])->name('home');
// login

// =====================================================================================
// AUTH (dari branch login)

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Logout (tetap GET sesuai branch login; idealnya POST jika pakai middleware auth)
Route::get('/logout', function () {
    // auth()->logout();
    return redirect('/login');
})->name('logout');

// =====================================================================================
// DASHBOARD (dari branch login)
Route::get('/buyee_admin_dashboard', function () {
    return view('buyee_admin_dashboard');
})->middleware('auth')->name('admin.dashboard');


// user
Route::get('/home', function () {
    return redirect()->route('home');
})->name('user.home')->middleware('auth');

Route::get('/buyee_user_dashboard', function () {
    return view('buyee_user_dashboard');

})->name('user.dashboard')->middleware('auth');

// =====================================================================================
// HALAMAN LAIN (dari branch login)
Route::get('/checkout', fn () => view('checkout'));
Route::get('/SearchResult', fn () => view('SearchResult'));

// =====================================================================================
// PROFILE (dari branch login, protected by auth)
Route::middleware(['auth'])->group(function () {

    
    // Main profile page
    Route::get('/user_profil', [ProfileController::class, 'index'])->name('user_profil')->middleware('auth');

    // Biodata management


    // Halaman profil utama
    Route::get('/user_profil', [ProfileController::class, 'index'])->name('user_profil.blade.php');

    // Biodata

    Route::get('/profile/biodata/edit', [ProfileController::class, 'editBiodata'])->name('profile.biodata.edit');
    Route::put('/profile/biodata', [ProfileController::class, 'updateBiodata'])->name('profile.biodata.update');

    // Kontak
    Route::get('/profile/contact/edit', [ProfileController::class, 'editContact'])->name('profile.contact.edit');
    Route::put('/profile/contact', [ProfileController::class, 'updateContact'])->name('profile.contact.update');

    // Foto profil
    Route::post('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.photo.update');

    // Alamat
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');

    // Metode pembayaran
    Route::get('/profile/payment-methods', [ProfileController::class, 'paymentMethods'])->name('profile.payment-methods');

    // Riwayat transaksi
    Route::get('/profile/transactions', [ProfileController::class, 'transactions'])->name('profile.transactions');

    // Notifikasi
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');

    // Wishlist

    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('wishlist');
    
    // Favorite stores

    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist');

    // Toko favorit

    Route::get('/profile/favorite-stores', [ProfileController::class, 'favoriteStores'])->name('profile.favorite-stores');

    // Pengaturan
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
});

// =====================================================================================
// API kecil untuk AJAX (dari branch login)
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/profile/wallet-balance', [ProfileController::class, 'getWalletBalance']);
    Route::get('/profile/notifications/unread-count', [ProfileController::class, 'getUnreadNotificationsCount']);
    Route::get('/profile/cart/items-count', [ProfileController::class, 'getCartItemsCount']);
});

// =====================================================================================
// ADMIN AREA (dari branch login)
Route::prefix('buyee-admin')->group(function () {
    // Dashboard
    Route::get('/dashboard/stats', [AdminController::class, 'getDashboardStats']);

    // Produk
    Route::get('/products', [AdminController::class, 'getProducts']);
    Route::get('/products/{id}', [AdminController::class, 'getProduct']);
    Route::post('/products/save', [AdminController::class, 'saveProduct']);
    Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct']);

    // Kategori
    Route::get('/categories', [AdminController::class, 'getCategories']);
    Route::get('/categories/{id}', [AdminController::class, 'getCategory']);
    Route::post('/categories/save', [AdminController::class, 'saveCategory']);
    Route::delete('/categories/delete/{id}', [AdminController::class, 'deleteCategory']);

    // Pesanan
    Route::get('/orders', [AdminController::class, 'getOrders']);
    Route::post('/orders/update-status', [AdminController::class, 'updateOrderStatus']);
});
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/product/{id}', [CatalogController::class, 'show'])->name('product.detail');




// =====================================================================================
// WISHLIST & CART (dari branch halaman-home)
Route::post('/wishlist/add', function (Request $r) {
    $item = $r->validate([
        'sku'   => 'required',
        'name'  => 'required',
        'price' => 'numeric'
    ]);
    $wishlist = session('wishlist', []);
    if (!collect($wishlist)->firstWhere('sku', $item['sku'])) {
        $wishlist[] = $item;
    }
    session(['wishlist' => $wishlist]);
    return response()->json(['ok' => true, 'count' => count($wishlist)]);
})->name('wishlist.add');

Route::post('/cart/add', function (Request $r) {
    $item = $r->validate([
        'sku'   => 'required',
        'name'  => 'required',
        'price' => 'numeric'
    ]);
    $cart = session('cart', []);
    if (!collect($cart)->firstWhere('sku', $item['sku'])) {
        $cart[] = $item + ['qty' => 1];
    }
    session(['cart' => $cart]);
    return response()->json(['ok' => true, 'count' => count($cart)]);
})->name('cart.add');

Route::view('/cart', 'pages.cart')->name('cart');

// =====================================================================================
// CATALOG & PRODUCT DETAIL (dari branch halaman-home)
// Catatan: kita pakai satu route product detail saja untuk hindari duplikasi nama/path.
Route::view('/catalog', 'pages.catalog')->name('catalog');
Route::get('/product/{slug?}', fn () => view('pages.product-detail'))->name('product.detail');
