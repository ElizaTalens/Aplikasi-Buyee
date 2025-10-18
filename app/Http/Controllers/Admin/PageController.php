<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // dashboard admin.
   
    public function dashboard()
    {
        return view('pages.dashboard');
    }

    //manajemen produk.
    public function products()
    {
        return view('admin.products');
    }

    //manajemen pesanan
    public function orders()
    {
        return redirect()->route('admin.page.dashboard')->with('activeSection', 'orders');
    }

    //manajemen kategori
    public function categories()
    {
        return view('admin.categories');
    }
}