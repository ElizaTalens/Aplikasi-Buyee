<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    // app/Http/Controllers/ProductController.php
// ...

    // Ini fungsi yang hilang
    public function index()
    {
        // Ambil data produk dan kategori, seperti yang dilakukan CatalogController
        $products = Product::where('is_active', true)->with('category')->get();
        $categories = Category::all();
        
        // Kembalikan view katalog
        return view('pages.catalog', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pages.product-detail', compact('product'));
    }
}
    
