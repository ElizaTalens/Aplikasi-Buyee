<?php


/*
|--------------------------------------------------------------------------
| CONTROLLERS
|--------------------------------------------------------------------------
*/
// Frontend Controllers

use Illuminate\Support\Facades\Route; 
use Illuminate\Http\Request;    
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;



use App\Http\Controllers\Auth\GoogleAuthController;

// Backend (Admin) Controllers
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;


/*
|--------------------------------------------------------------------------
| RUTE PUBLIK & PENGGUNA
|--------------------------------------------------------------------------
*/
Route::middleware(\App\Http\Middleware\RedirectIfAdmin::class)->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/catalog', [ProductController::class, 'index'])->name('catalog');
    Route::get('/product/{product:slug}', [ProductController::class, 'show'])->name('product.detail');
    Route::get('/search', [ProductController::class, 'search'])->name('products.search');
});

// Rute untuk pengguna yang BELUM LOGIN
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login'); // REKOMENDASI: Mengganti nama 'login.form' menjadi 'login'
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
    Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
    Route::post('/register', [RegisterController::class, 'register'])->name('register');
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// Rute untuk pengguna yang SUDAH LOGIN
Route::middleware(['auth', \App\Http\Middleware\RedirectIfAdmin::class])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // FIX: Rute riwayat pesanan dipindahkan ke sini agar terproteksi
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show')->whereNumber('order');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel')->whereNumber('order');
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');
    // Session-based cart count (JSON)
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
    
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle'); // Rute utama untuk tambah/hapus
    Route::post('/wishlist/check', [WishlistController::class, 'check'])->name('wishlist.check'); // Untuk cek status awal
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->name('wishlist.destroy'); // Hapus berdasarkan ID wishlist
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear'); // Kosongkan wishlist
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count'); // Hitung jumlah
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    // FIX: Tambahkan route POST untuk memproses checkout
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
});


/*
|--------------------------------------------------------------------------
| RUTE PANEL ADMIN (Butuh Login & Peran Admin)
|--------------------------------------------------------------------------
*/

// Temporary test routes for debugging
Route::get('/test-auth', function () {
    return response()->json([
        'authenticated' => Auth::check(),
       'user' => Auth::user()
            ? collect(Auth::user())->only(['id', 'name', 'email'])->all()
            : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});

Route::get('/auto-login', function () {
    $user = \App\Models\User::where('email', 'test@example.com')->first();
    if ($user) {
        Auth::login($user);
        return redirect('/wishlist')->with('success', 'Berhasil login sebagai test user');
    }
    return redirect('/login')->with('error', 'Test user tidak ditemukan');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // --- Rute untuk menampilkan HALAMAN Admin (Blade Views) ---
    Route::get('/dashboard', [AdminPageController::class, 'dashboard'])->name('page.dashboard');
    Route::get('/products', [AdminPageController::class, 'products'])->name('page.products');
    Route::get('/orders', [AdminPageController::class, 'orders'])->name('page.orders');
    Route::get('/categories', [AdminPageController::class, 'categories'])->name('page.categories');
    
    // --- Rute API yang dipanggil oleh JavaScript (SEMUA MENGARAH KE AdminController) ---
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/stats', [AdminController::class, 'getDashboardStats'])->name('stats');
        Route::get('/products', [AdminController::class, 'getProducts'])->name('products.index');
        Route::get('/products/{id}', [AdminController::class, 'getProduct'])->name('products.show');
        Route::post('/products/save', [AdminController::class, 'saveProduct'])->name('products.save');
        Route::delete('/products/delete/{id}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        Route::get('/categories', [AdminController::class, 'getCategories'])->name('categories.index');
        Route::get('/categories/{id}', [AdminController::class, 'getCategory'])->name('categories.show');
        Route::post('/categories/save', [AdminController::class, 'saveCategory'])->name('categories.save');
        Route::delete('/categories/delete/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');
        Route::get('/orders', [AdminController::class, 'getOrders'])->name('orders.index');
        Route::post('/orders/update-status', [AdminController::class, 'updateOrderStatus'])->name('orders.update_status');
    });
});

// login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

// register
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// admin
Route:: get ('/buyee_admin_dashboard', function () {
    return view('buyee_admin_dashboard');
})->middleware('auth');

// user
Route::get('/buyee_user_dashboard', function () {
    return view('buyee_user_dashboard');
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
    Route::get('/user_profil', [ProfileController::class, 'index'])->name('user_profil.blade.php');
    
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
    Route::get('/profile/transactions', [ProfileController::class, 'transactions'])->name('profile.transactions');
    
    // Notifications
    Route::get('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    
    // Wishlist
    Route::get('/profile/wishlist', [ProfileController::class, 'wishlist'])->name('profile.wishlist');
    
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
Route::get('/logout', function () {
    // auth()->logout(); // Mengakhiri sesi pengguna
    return redirect('/login'); // Mengarahkan kembali ke halaman login
})->name('logout');