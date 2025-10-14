<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth')->group(function () {
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle']);
    Route::post('/wishlist/check', [WishlistController::class, 'check']);
});

// Debug route untuk testing
Route::get('/debug/auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'user' => auth()->user() ? auth()->user()->only(['id', 'name', 'email']) : null,
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});

// Public API routes
Route::prefix('v1')->group(function () {
    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/active', [CategoryController::class, 'active']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);

    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/category/{categoryId}', [ProductController::class, 'byCategory']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
});

// Protected API routes (require authentication)
Route::middleware(['auth'])->prefix('v1')->group(function () {
    // Count endpoints (require auth to get accurate counts)
    Route::get('/cart/count', [CartController::class, 'count']);
    Route::get('/wishlist/count', [WishlistController::class, 'count']);
    
    // Cart management
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::post('/cart/add', [CartController::class, 'store']); // Alias for cart add
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Wishlist management
    Route::get('/wishlist', [WishlistController::class, 'index']);
    Route::post('/wishlist', [WishlistController::class, 'store']);
    Route::post('/wishlist/toggle', [WishlistController::class, 'store']); // Alias for wishlist toggle
    Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy']);
    Route::delete('/wishlist/product', [WishlistController::class, 'removeByProduct']);
    Route::delete('/wishlist', [WishlistController::class, 'clear']);
    Route::post('/wishlist/check', [WishlistController::class, 'check']);

    // Admin routes for product and category management
    Route::prefix('admin')->middleware('admin')->group(function () {
        // Admin dashboard stats
        Route::get('/stats', [AdminController::class, 'getDashboardStats']);
        
        // Admin product management
        Route::get('/products', [AdminController::class, 'getProducts']);
        Route::get('/products/{id}', [AdminController::class, 'getProduct']);
        Route::post('/products/save', [AdminController::class, 'saveProduct']);
        Route::delete('/products/{id}', [AdminController::class, 'deleteProduct']);
        
        // Admin category management
        Route::get('/categories', [AdminController::class, 'getCategories']);
        Route::post('/categories/save', [AdminController::class, 'saveCategory']);
        Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory']);
        
        // Admin order management
        Route::get('/orders', [AdminController::class, 'getOrders']);
        Route::post('/orders/update-status', [AdminController::class, 'updateOrderStatus']);
    });
});