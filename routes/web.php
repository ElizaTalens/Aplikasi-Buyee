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
