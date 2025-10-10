<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index()
    {
        // Fungsi ini akan menampilkan file view Anda
        return view('search_result'); 
    }
}