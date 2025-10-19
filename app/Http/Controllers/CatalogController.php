<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View; 

class CatalogController extends Controller
{
    /**
     * Menampilkan daftar produk di katalog dengan kemampuan filter.
     */
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
    
        $productsQuery = Product::where('is_active', true)
                                ->with('category')
                                ->latest(); 

        if ($categorySlug) {
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                $productsQuery->where('category_id', $category->id);
            }
        }

        $products = $productsQuery->get(); 
        
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