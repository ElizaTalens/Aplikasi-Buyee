<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session; 

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.navbar', function ($view) {
            $cartCount = 0;
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->sum('quantity');
            }
            
            // Ambil history pencarian dari session
            $searchHistory = Session::get('search_history', []);
            
            // Kirim kedua variabel ke view navbar
            $view->with('cartCount', $cartCount)
                 ->with('searchHistory', $searchHistory);
        });
    }
}