<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception; // Tambahkan import untuk Exception
use Illuminate\Validation\ValidationException; // Tambahkan import untuk ValidationException
use Illuminate\Support\Facades\Log;

class WishlistController extends Controller
{
    /**
     * Menampilkan halaman Wishlist (Web Route).
     */
    public function index(): View
    {
        // Pastikan pengguna sudah login karena route ini dilindungi oleh middleware 'auth'
        $wishlistItems = Wishlist::with('product') 
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('pages.wishlist', compact('wishlistItems'));
    }

    /**
     * Menambah atau menghapus item dari Wishlist (AJAX/API).
     * Route ini HARUS dilindungi oleh middleware('auth').
     */
    public function store(Request $request): JsonResponse
    {
        // Pengecekan Auth::check() dihapus, karena 'auth' middleware akan mengalihkan 
        // atau mengembalikan 401 JSON secara otomatis jika header AJAX dikirim.

        try {
            // 1. Validasi
            $request->validate([ 
                'product_id' => 'required|exists:products,id',
            ]);

            $userId = Auth::id(); // Sudah pasti ada karena melalui middleware 'auth'
            $productId = $request->product_id;

            // 2. Toggle Logika: Cari item Wishlist
            $wishlistItem = Wishlist::where('user_id', $userId)
                                     ->where('product_id', $productId)
                                     ->first();

            if ($wishlistItem) {
                // Hapus (Toggle Off)
                $wishlistItem->delete(); 
                $action = 'removed';
                $message = 'Produk dihapus dari wishlist.';
            } else {
                // Tambah (Toggle On)
                Wishlist::create([ 
                    'user_id' => $userId, 
                    'product_id' => $productId,
                ]);
                $action = 'added';
                $message = 'Produk berhasil ditambahkan ke wishlist!';
            }
            
            // 3. Sukses Response
            return response()->json([ 
                'message' => $message, 
                'action' => $action, 
                'count' => Wishlist::where('user_id', $userId)->count()
            ], 200);
            
        } catch (ValidationException $e) {
            // Jika validasi gagal (Laravel biasanya menangani ini secara otomatis, tapi di sini untuk kejelasan)
            return response()->json([
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            // 4. Penanganan Error Fatal (PENTING untuk mencegah respons HTML 500)
            
            // Log error untuk kebutuhan debugging di server
            Log::error("Wishlist Store Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            // Kembalikan respons JSON 500
            return response()->json([
                'message' => 'Gagal memperbarui Wishlist: Terjadi kesalahan pada server.',
                // Tampilkan pesan error hanya jika di lingkungan development/debug
                'debug_message' => env('APP_DEBUG') ? $e->getMessage() : null,
                'action' => 'error'
            ], 500);
        }
    }
}