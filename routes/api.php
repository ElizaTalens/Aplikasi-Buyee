<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| Autentikasi API
|--------------------------------------------------------------------------
|
| Rute-rute ini dilindungi oleh middleware 'auth:sanctum'.
| Permintaan harus menyertakan token API yang valid (Bearer Token)
| ATAU menggunakan Autentikasi Stateful Sanctum untuk SPA/Web.
|
*/

// Group rute yang memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    
    // API endpoint untuk menambah/menghapus item Wishlist
    // URL aksesnya akan menjadi: /api/wishlist
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('api.wishlist.store');
    
    // Opsional: API endpoint untuk melihat daftar Wishlist (jika diperlukan oleh JS)
    // URL aksesnya akan menjadi: /api/wishlist
    Route::get('/wishlist', [WishlistController::class, 'indexApi'])->name('api.wishlist.index');
    
    // Opsional: API endpoint untuk menghapus item spesifik
    // URL aksesnya akan menjadi: /api/wishlist/{product_id}
    // Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('api.wishlist.destroy');
});

// Anda bisa menambahkan route publik di luar middleware group ini
// Route::get('/products', [ProductController::class, 'index']);