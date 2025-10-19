<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        // Kategori (untuk section kategori)
        $categories = Category::orderBy('name')->get();

        // Produk terbaru
        $newArrivals = Product::where('is_active', 1)
            ->orderByDesc('created_at')
            ->take(12)
            ->get();

        // Produk terlaris: jumlah qty dari order_items (hanya order dengan status 'selesai')
        $bestsellers = Product::select('products.*', DB::raw('(
            SELECT COALESCE(SUM(oi.qty),0)
            FROM order_items AS oi
            JOIN orders AS o ON oi.order_id = o.id
            WHERE oi.product_id = products.id
              AND o.status = "selesai"
        ) AS total_sold'))
            ->where('is_active', 1)
            ->orderByDesc('total_sold')
            ->take(12)
            ->get();

        return view('pages.home', compact('categories', 'newArrivals', 'bestsellers'));
    }
}