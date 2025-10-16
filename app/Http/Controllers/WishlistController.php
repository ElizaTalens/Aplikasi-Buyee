<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $wishlistItems = Wishlist::with('product') 
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.wishlist', compact('wishlistItems'));
    }

    // app/Http/Controllers/WishlistController.php

// ... (kode di atas tetap sama)

    public function store(Request $request): JsonResponse
    {
        // 1. Cek Login (Penting!)
        if (!Auth::check()) {
            // Jika tidak login, kirim 401 Unauthorized secara eksplisit (lebih baik)
            return response()->json(['message' => 'Silakan login untuk menambahkan ke Wishlist.'], 401); 
        }

        // ... (kode validasi dan toggle di bawahnya tetap sama)

        $request->validate([ //
            'product_id' => 'required|exists:products,id', //
        ]); //

        $userId = Auth::id(); //
        $productId = $request->product_id; //

        $wishlistItem = Wishlist::where('user_id', $userId) //
                                     ->where('product_id', $productId) //
                                     ->first(); //

        if ($wishlistItem) { //
            $wishlistItem->delete(); //
            $action = 'removed'; //
            $message = 'Produk dihapus dari wishlist.'; //
        } else { //
            Wishlist::create([ //
                'user_id' => $userId, //
                'product_id' => $productId, //
            ]); //
            $action = 'added'; //
            $message = 'Produk berhasil ditambahkan ke wishlist!'; //
        }
        
        return response()->json([ //
            'message' => $message, //
            'action' => $action, //
            'count' => Wishlist::where('user_id', $userId)->count() //
        ]); //
    }

}