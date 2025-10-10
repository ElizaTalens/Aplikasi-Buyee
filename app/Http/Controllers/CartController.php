<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja user.
     */
    public function index()
    {
        $userId = Auth::id();
        $cartItems = CartItem::where('user_id', $userId)->with('product')->get();
        return view('cart', compact('cartItems'));
    }

    /**
     * Menambah produk ke keranjang.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'product_id' => $request->product_id
            ],
            [
                'quantity' => $request->quantity
            ]
        );

        return redirect()->route('cart')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
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