<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (homepage).
     */
    public function index(): View
    {
        // Data untuk komponen "New Arrival" & "Bestseller"
        $newArrivals = Product::where('is_active', true)->latest()->take(8)->get();
        $bestsellers = Product::where('is_active', true)->orderBy('sales_count', 'desc')->take(8)->get();

        // Data untuk komponen "Browse By Category"
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->get();

        // Kirim SEMUA data yang dibutuhkan ke view 'pages.home'
        return view('pages.home', compact('newArrivals', 'bestsellers', 'categories'));
    }
}