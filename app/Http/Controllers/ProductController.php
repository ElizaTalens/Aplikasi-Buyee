<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::all();
        $query = Product::with('category')->where('is_active', true);
        
        // --- LOGIKA UNTUK JUDUL HALAMAN & FILTER KATEGORI ---
        $pageTitle = 'All Products'; // Judul default
        
        if ($request->filled('group') && $request->group !== 'all') {
            // Asumsi slug di database adalah 'women-fashion', 'men-fashion', dll.
            $groupSlug = Str::slug($request->group . ' fashion');
            
            // 1. Cari kategori yang cocok untuk dijadikan judul halaman
            $activeCategory = $categories->firstWhere('slug', $groupSlug);
            if ($activeCategory) {
                $pageTitle = $activeCategory->name;
            }
            
            // 2. Terapkan filter ke query produk
            $query->whereHas('category', function($q) use ($groupSlug) {
                $q->where('slug', $groupSlug);
            });
        }
        
        // Filter Harga
        if ($request->filled('min_price')) { /* ... */ }
        if ($request->filled('max_price')) { /* ... */ }
        
        // Fitur Pencarian & History
        if ($request->filled('search')) {
            // ... logika pencarian & history ...
        }

        // Logika Sorting
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'bestseller': $query->orderBy('sales_count', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        $products = $query->paginate(9)->withQueryString();

        // Kirim semua data yang dibutuhkan, termasuk $pageTitle
        return view('pages.catalog', compact('products', 'categories', 'pageTitle'));
    }

    /**
     * Menampilkan halaman detail untuk satu produk.
     */
    public function show(Product $product): View
    {
        if (!$product->is_active) {
            abort(404);
        }
        $product->load('category');
        return view('products.show', compact('product'));
    }
}