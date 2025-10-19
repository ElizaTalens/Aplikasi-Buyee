<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Session; 

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('layouts.navbar', function ($view) {
            $cartCount = 0;
            $wishlistCount = 0;
            
            if (Auth::check()) {
                $cartCount = CartItem::where('user_id', Auth::id())->count();
                $wishlistCount = Wishlist::where('user_id', Auth::id())->count();
            }
            
            // Ambil history pencarian dari session
            $searchHistory = Session::get('search_history', []);
            
            // Kirim variabel ke view navbar
            $view->with('cartCount', $cartCount)
                 ->with('wishlistCount', $wishlistCount)
                 ->with('searchHistory', $searchHistory);
        });
    }
}