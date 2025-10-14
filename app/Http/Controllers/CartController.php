<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Tambahkan ini untuk Transaksi Database

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja pengguna.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(): View|JsonResponse
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        // Jika request mengharapkan JSON (misalnya dari API client)
        if (request()->expectsJson()) {
            return response()->json([
                'cart_items' => $cartItems,
                'total' => $total,
                'count' => $this->getCartCount()
            ]);
        }
        
        // Menampilkan view untuk browser
        return view('pages.cart', compact('cartItems', 'total'));
    }

    /**
     * Menambahkan item baru ke dalam keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_options' => 'nullable|array'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Validasi ketersediaan produk
        if (!$product->is_active || $product->stock_quantity < $validated['quantity']) {
            return response()->json(['message' => 'Stok produk tidak mencukupi atau tidak tersedia.'], 422);
        }

        try {
            DB::beginTransaction();

            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $validated['product_id'])
                ->first();

            if ($cartItem) {
                // Jika item sudah ada, update kuantitasnya
                $newQuantity = $cartItem->quantity + $validated['quantity'];
                if ($product->stock_quantity < $newQuantity) {
                    throw new \Exception('Stok tidak mencukupi untuk menambahkan kuantitas.');
                }
                $cartItem->quantity = $newQuantity;
                $cartItem->price = $product->final_price; // Selalu update harga terbaru
                $cartItem->product_options = $validated['product_options'] ?? [];
                $cartItem->save();
            } else {
                // Jika item baru, buat entri baru
                $cartItem = CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $validated['product_id'],
                    'quantity' => $validated['quantity'],
                    'price' => $product->final_price,
                    'product_options' => $validated['product_options'] ?? []
                ]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Produk berhasil ditambahkan ke keranjang!',
                'cart_item' => $cartItem->load('product'),
                'cart_count' => $this->getCartCount() // <-- Mengembalikan jumlah terbaru
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Memperbarui kuantitas item di keranjang.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id (CartItem ID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            DB::beginTransaction();

            $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
            $product = $cartItem->product;

            if ($product->stock_quantity < $validated['quantity']) {
                throw new \Exception('Stok produk tidak mencukupi.');
            }

            $cartItem->update([
                'quantity' => $validated['quantity'],
                'price' => $product->final_price // Update harga jika berubah
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Kuantitas berhasil diperbarui.',
                'cart_item' => $cartItem->load('product'),
                'cart_count' => $this->getCartCount() // <-- Mengembalikan jumlah terbaru
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    /**
     * Menghapus item dari keranjang.
     *
     * @param  string  $id (CartItem ID)
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        $cartItem = CartItem::where('user_id', Auth::id())->findOrFail($id);
        $cartItem->delete();

        return response()->json([
            'message' => 'Item berhasil dihapus dari keranjang.',
            'cart_count' => $this->getCartCount() // <-- Mengembalikan jumlah terbaru
        ]);
    }

    /**
     * Menghapus semua item dari keranjang.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clear(): JsonResponse
    {
        CartItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => 'Semua item berhasil dihapus dari keranjang.',
            'cart_count' => 0 // <-- Langsung kembalikan 0
        ]);
    }

    /**
     * Mendapatkan jumlah total kuantitas item di keranjang.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json(['count' => $this->getCartCount()]);
    }

    /**
     * Helper method untuk menghitung total kuantitas item di keranjang.
     *
     * @return int
     */
    private function getCartCount(): int
    {
        if (!Auth::check()) {
            return 0;
        }
        // Menjumlahkan total kuantitas, bukan hanya jumlah baris/produk
        return CartItem::where('user_id', Auth::id())->sum('quantity');
    }
}