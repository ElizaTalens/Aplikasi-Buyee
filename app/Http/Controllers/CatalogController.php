<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\View\View; // Tambahkan ini jika Anda menggunakan type hinting

class CatalogController extends Controller
{
    /**
     * Menampilkan daftar produk di katalog dengan kemampuan filter.
     */
    // >> KOREKSI: Tambahkan parameter Request untuk menerima query string dari URL
    public function index(Request $request)
    {
        // Ambil slug kategori dari URL parameter, jika ada (?category=slug-kategori)
        $categorySlug = $request->query('category');
        
        // 1. Mulai query produk, pastikan is_active=true dan eager loading kategori
        $productsQuery = Product::where('is_active', true)
                                ->with('category')
                                ->latest(); // Urutkan terbaru di atas

        // 2. Terapkan Filter Kategori
        if ($categorySlug) {
            // Cari ID kategori berdasarkan slug
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                // Filter produk berdasarkan category_id yang ditemukan
                $productsQuery->where('category_id', $category->id);
            }
            // Jika slug tidak ditemukan, tidak ada filter yang diterapkan
        }

        // 3. Ambil hasil query (gunakan get() atau paginate() di sini)
        $products = $productsQuery->get(); 
        
        // Ambil semua kategori untuk sidebar/filter di halaman katalog
        $categories = Category::all();
        
        // Kirim data ke view
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