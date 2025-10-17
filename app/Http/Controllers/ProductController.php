<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk di katalog, menangani pencarian, dan filter kategori.
     */
    public function index(Request $request)
    {
        // 1. Ambil input filter dari URL
        $searchQuery = $request->query('search') ?? $request->query('q');
        $categoryId = $request->query('category_id');
        $minPrice = $request->query('min_price'); // Ambil filter harga
        $maxPrice = $request->query('max_price'); // Ambil filter harga
        $sortBy = $request->query('sort');       // Ambil filter sortir

        // 2. Mulai Query: hanya tampilkan produk yang aktif
        $productsQuery = Product::where('is_active', true)->with('category');

        // 3. Logika Filter: PENCARIAN
        if ($searchQuery) {
            $productsQuery->where(function ($queryBuilder) use ($searchQuery) {
                $queryBuilder->where('name', 'like', '%' . $searchQuery . '%')
                             ->orWhereHas('category', function ($q) use ($searchQuery) {
                                $q->where('name', 'like', '%' . $searchQuery . '%');
                            });
            });
        }

        // 4. Logika Filter: KATEGORI
        if ($categoryId) {
            $productsQuery->where('category_id', $categoryId);
        }
        
        // 5. ✨ PERBAIKAN Logika Filter: HARGA
        if ($minPrice) {
            $productsQuery->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $productsQuery->where('price', '<=', $maxPrice);
        }
        
        // 6. Logika Sortir (Contoh Bestseller/Harga)
        if ($sortBy == 'bestseller') {
             // Asumsi: Urutkan berdasarkan kolom 'stock' atau 'sales_count'
            $productsQuery->orderByDesc('stock'); 
        } else {
            // Default: Urutkan berdasarkan yang terbaru
            $productsQuery->latest();
        }


        // 7. Eksekusi Query
        $products = $productsQuery->get();
        $categories = Category::all();

        // Mengembalikan view
        return view('pages.catalog', compact('products', 'categories'));
    }
    
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('pages.product-detail', compact('product'));
    }
}