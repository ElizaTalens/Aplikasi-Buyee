<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman Home dengan daftar produk dan kategori.
     */
    public function index()
    {
        // Mengambil produk terbaru yang aktif (is_active = 1)
        // Eager loading relasi 'category' untuk efisiensi.
        $products = Product::where('is_active', true)
                           ->with('category')
                           ->orderBy('created_at', 'desc')
                           ->get(); 
        
        // Mengambil semua kategori (bisa ditambahkan withCount('products') jika diperlukan)
        $categories = Category::all();

        // Mengembalikan view 'home.blade.php' dan membawa data
        return view('pages.home', [
            'products' => $products,
            'categories' => $categories,
            
        ]);
    }
}