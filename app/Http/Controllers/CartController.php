<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use Illuminate\Http\JsonResponse;
use App\Models\Product; // Pastikan Product diimpor

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja user.
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();
        return view('pages.cart', compact('cartItems'));
    }

    /**
     * Menambah produk ke keranjang via AJAX.
     */
    public function store(Request $request): JsonResponse
    {
        // Pengecekan Login Eksplisit
        if (!Auth::check()) {
            return response()->json(['message' => 'Silakan login terlebih dahulu.'], 401); 
        }
        
        // 1. Validasi input
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();
        $productId = $validated['product_id'];
        $quantity = (int)$validated['quantity'];

        // 2. Cek ketersediaan stok
        $product = Product::find($productId);
        if (!$product || $product->stock < $quantity) {
             return response()->json([
                'message' => 'Stok tidak cukup untuk jumlah yang diminta.',
                'count' => (int)CartItem::where('user_id', $userId)->sum('quantity')
            ], 400); 
        }

        // 3. Simpan/Update CartItem
        try {
            $cartItem = CartItem::where('user_id', $userId)
                                ->where('product_id', $productId)
                                ->first();

            if ($cartItem) {
                $cartItem->quantity += $quantity;
                $cartItem->save();
                $action = 'updated';
            } else {
                CartItem::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                    'quantity' => $quantity
                ]);
                $action = 'added';
            }

            // 4. Hitung total item cart
            $newCount = CartItem::where('user_id', $userId)->sum('quantity');

            // 5. Kembalikan respons JSON Sukses
            return response()->json([
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'action' => $action,
                'count' => (int)$newCount
            ]);
            
        } catch (\Exception $e) {
            // Tangkap exception database jika terjadi (misal kolom hilang)
             return response()->json([
                'message' => 'Gagal menyimpan data keranjang. Error internal: ' . $e->getMessage(),
                'count' => (int)CartItem::where('user_id', $userId)->sum('quantity')
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Menghapus item dari keranjang.
     */
    public function remove($id)
    {
        $item = CartItem::where('id', $id)->where('user_id', Auth::id())->first();
        if ($item) {
            $item->delete();
        }
        return redirect()->route('cart')->with('success', 'Produk dihapus dari keranjang.');
    }
}
