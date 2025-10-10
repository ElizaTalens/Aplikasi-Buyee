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

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $wishlistItem = Wishlist::where('user_id', $userId) // <-- GANTI INI
                                     ->where('product_id', $productId)
                                     ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            $action = 'removed';
        } else {
            Wishlist::create([ // <-- GANTI INI
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            $action = 'added';
        }
        
        return response()->json([
            'message' => 'Wishlist updated successfully.',
            'action' => $action,
            'count' => Wishlist::where('user_id', $userId)->count() // <-- GANTI INI
        ]);
    }
}