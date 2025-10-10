<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Menampilkan detail produk.
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('product-detail', compact('product'));
    }
}