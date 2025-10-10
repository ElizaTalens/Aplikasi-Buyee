<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class CatalogController extends Controller
{
    /**
     * Menampilkan daftar produk di katalog.
     */
    public function index()
    {
        $products = Product::where('is_active', true)->with('category')->get();
        $categories = Category::all();
        return view('catalog', compact('products', 'categories'));
    }

    /**
     * Menampilkan detail produk.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pages.product-detail', compact('product'));
    }
}