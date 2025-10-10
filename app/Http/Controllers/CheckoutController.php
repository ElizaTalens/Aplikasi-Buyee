<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        // PENTING: Jika keranjang kosong, pengguna akan dikembalikan ke halaman cart
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $deliveryFee = 15000; // Contoh
        $total = $subtotal + $deliveryFee;

        // Jika keranjang ada isinya, tampilkan halaman checkout
        return view('pages.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'total'));
    }
}