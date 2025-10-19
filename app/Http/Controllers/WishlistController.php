<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Menampilkan halaman wishlist pengguna.
     */
    public function index(): View
    {
        $wishlistItems = Wishlist::with('product') 
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.wishlist', compact('wishlistItems'));
    }

    /**
     * Menambah atau menghapus item dari wishlist (fungsi toggle).
     */
    public function toggle(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        $product_id = $request->input('product_id');
        $user_id = Auth::id();

        // Cek apakah item sudah ada di wishlist
        $wishlist = Wishlist::where('user_id', $user_id)
                           ->where('product_id', $product_id)
                           ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Produk dihapus dari wishlist';
        } else {
            Wishlist::create([
                'user_id' => $user_id,
                'product_id' => $product_id
            ]);
            $message = 'Produk ditambahkan ke wishlist';
        }

        // Dapatkan jumlah terbaru
        $count = Wishlist::where('user_id', $user_id)->count();

        return response()->json([
            'message' => $message,
            'count' => $count
        ]);
    }

    /**
     * Memeriksa apakah sebuah produk ada di wishlist pengguna.
     */
    public function check(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);

        $inWishlist = Wishlist::where('user_id', Auth::id())
                              ->where('product_id', $request->product_id)
                              ->exists(); // exists() lebih efisien daripada first() atau count()

        return response()->json(['in_wishlist' => $inWishlist]);
    }

    /**
     * Menghapus item berdasarkan ID unik wishlist.
     */
    public function destroy(string $id): JsonResponse
    {
        // findOrFail akan otomatis memberikan error 404 jika tidak ditemukan
        $wishlistItem = Wishlist::where('user_id', Auth::id())->findOrFail($id);
        $wishlistItem->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus dari wishlist.',
            'count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }

    /**
     * Menghapus semua item dari wishlist pengguna.
     */
    public function clear(): JsonResponse
    {
        $deletedCount = Wishlist::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => "Berhasil menghapus {$deletedCount} item dari wishlist.",
            'count' => 0
        ]);
    }

    /**
     * Mendapatkan jumlah item di wishlist.
     */
    public function count(): JsonResponse
    {
        $count = Auth::check() ? Wishlist::where('user_id', Auth::id())->count() : 0;
        return response()->json(['count' => $count]);
    }
}