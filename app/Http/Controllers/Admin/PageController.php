<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard admin.
     */
    public function dashboard()
    {
        // Mengembalikan view yang ada di: resources/views/admin/dashboard.blade.php
        return view('pages.dashboard');
    }

    /**
     * Menampilkan halaman manajemen produk.
     */
    public function products()
    {
        return view('admin.products');
    }

    /**
     * Menampilkan halaman manajemen pesanan.
     * Redirect ke dashboard karena kelola pesanan sudah terintegrasi di dashboard.
     */
    public function orders()
    {
        return redirect()->route('admin.page.dashboard')->with('activeSection', 'orders');
    }

    /**
     * Menampilkan halaman manajemen kategori.
     */
    public function categories()
    {
        return view('admin.categories');
    }
}