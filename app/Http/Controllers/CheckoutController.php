<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $total = $subtotal;
        return view('pages.checkout', compact('cartItems', 'subtotal', 'total'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
            'payment_method' => 'required|in:cod,transfer,qris',
            'order_notes' => 'nullable|string|max:1000'
        ]);

        try {
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get();

            if ($cartItems->isEmpty()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'error' => 'Keranjang Anda kosong.'
                    ], 422);
                }
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
            }

            $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
            $total = $subtotal;
            $order = DB::transaction(function () use ($validated, $cartItems, $total) {
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'],
                    'total' => $total,
                    'status' => 'pending',
                    'payment_method' => $validated['payment_method'],
                    'order_notes' => $validated['order_notes'] ?? null,
                    'address_text' => $validated['shipping_address'] . ', ' . 
                                    $validated['city'] . ', ' . 
                                    $validated['province'] . ' ' . 
                                    $validated['postal_code']
                ]);

                foreach ($cartItems as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'price' => $cartItem->product->price,
                        'qty' => $cartItem->quantity,
                        'subtotal' => $cartItem->quantity * $cartItem->product->price
                    ]);
                }

                CartItem::where('user_id', Auth::id())->delete();
                
                return $order;
            });

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesanan berhasil dibuat!',
                    'order_id' => $order->id,
                    'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'redirect' => route('orders.index'),
                    'payment_method' => $validated['payment_method'],
                ]);
            }

            return redirect()
                ->route('orders.index')
                ->with('success', 'Pesanan berhasil dibuat! Anda dapat melihat status pesanan di halaman ini.');

        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => 'Terjadi kesalahan saat memproses pesanan.'
                ], 500);
            }
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan.');
        }
    }
}
