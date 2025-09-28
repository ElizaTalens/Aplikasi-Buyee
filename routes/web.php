<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Controller
Route::get('/', fn () => view('pages.home'))->name('home');


// Product Detail (temporary static route)
Route::get('/product/{slug?}', fn () => view('pages.product-detail'))
     ->name('product.detail');

Route::get('/product/men-army-tee', [ProductDetailController::class, 'show'])
     ->name('product.detail');

// Wishlist & Cart
Route::post('/wishlist/add', function (Request $r) {
    $item = $r->validate(['sku'=>'required', 'name'=>'required', 'price'=>'numeric']);
    $wishlist = session('wishlist', []);
    if (!collect($wishlist)->firstWhere('sku', $item['sku'])) $wishlist[] = $item;
    session(['wishlist' => $wishlist]);
    return response()->json(['ok'=>true, 'count'=>count($wishlist)]);
})->name('wishlist.add');

Route::post('/cart/add', function (Request $r) {
    $item = $r->validate(['sku'=>'required', 'name'=>'required', 'price'=>'numeric']);
    $cart = session('cart', []);
    if (!collect($cart)->firstWhere('sku', $item['sku'])) $cart[] = $item + ['qty'=>1];
    session(['cart' => $cart]);
    return response()->json(['ok'=>true, 'count'=>count($cart)]);
})->name('cart.add');

// Cart 
Route::view('/cart', 'pages.cart')->name('cart');

// Browse by Category (Catalog)
Route::view('/catalog', 'pages.catalog')->name('catalog');
Route::view('/product/{slug}', 'pages.product-detail')->name('product.show');
