<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product; // Pastikan model Product di-import
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
            ->get(); // [cite: 722-724]

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.'); // [cite: 725-727]
        }

        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price); // [cite: 728]
        $total = $subtotal; // [cite: 729]
        return view('pages.checkout', compact('cartItems', 'subtotal', 'total')); // [cite: 730-731]
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255', // [cite: 735]
            'customer_email' => 'required|email|max:255', // [cite: 736]
            'customer_phone' => 'required|string|max:20', // [cite: 737]
            'shipping_address' => 'required|string', // [cite: 738]
            'city' => 'required|string|max:100', // [cite: 739]
            'province' => 'required|string|max:100', // [cite: 740]
            'postal_code' => 'required|string|max:10', // [cite: 741]
            'payment_method' => 'required|in:cod,transfer,qris', // [cite: 742]
            'order_notes' => 'nullable|string|max:1000' // [cite: 743]
        ]);

        try {
            $cartItems = CartItem::with('product')
                ->where('user_id', Auth::id())
                ->get(); // [cite: 746-748]

            if ($cartItems->isEmpty()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'error' => 'Keranjang Anda kosong.' // [cite: 752]
                    ], 422); // [cite: 753]
                }
                return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.'); // [cite: 755]
            }

            $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price); // [cite: 757]
            $total = $subtotal; // [cite: 758]

            $order = DB::transaction(function () use ($validated, $cartItems, $total) {
                
                // 1. BUAT ORDER BARU
                $order = Order::create([
                    'user_id' => Auth::id(), // [cite: 761]
                    'customer_name' => $validated['customer_name'], // [cite: 762]
                    'customer_email' => $validated['customer_email'], // [cite: 763]
                    'customer_phone' => $validated['customer_phone'], // [cite: 764]
                    'total' => $total, // [cite: 765]
                    'status' => 'pending', // [cite: 766]
                    'payment_method' => $validated['payment_method'], // [cite: 767]
                    'order_notes' => $validated['order_notes'] ?? null, // [cite: 768]
                    'address_text' => $validated['shipping_address'] . ', ' .
                                      $validated['city'] . ', ' .
                                      $validated['province'] . ' ' .
                                      $validated['postal_code'] // [cite: 769-772]
                ]);

                // 2. PROSES ITEM KERANJANG
                foreach ($cartItems as $cartItem) {
                    $product = $cartItem->product; 

                    // --- VALIDASI DAN PENGURANGAN STOK DITAMBAHKAN DI SINI ---
                    // Cek ketersediaan stok terakhir (Meskipun sudah dicek di keranjang, ini penting untuk transaksi)
                    if ($product->stock_quantity < $cartItem->quantity) {
                        // Throw exception akan memicu DB::rollBack()
                        throw new \Exception("Stok produk '{$product->name}' tidak mencukupi ({$product->stock_quantity} tersedia).");
                    }
                    
                    // Buat Order Item
                    OrderItem::create([
                        'order_id' => $order->id, // [cite: 776]
                        'product_id' => $cartItem->product_id, // [cite: 777]
                        'price' => $product->price, // [cite: 778]
                        'qty' => $cartItem->quantity, // [cite: 779]
                        'subtotal' => $cartItem->quantity * $product->price // [cite: 780]
                    ]);
                    
                    // KURANGI STOK PRODUK
                    $product->decrement('stock_quantity', $cartItem->quantity);
                }
                
                // 3. HAPUS ITEM DARI KERANJANG
                CartItem::where('user_id', Auth::id())->delete(); // [cite: 783]

                return $order;
            }); // [cite: 759, 785]

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true, // [cite: 788]
                    'message' => 'Pesanan berhasil dibuat!', // [cite: 789]
                    'order_id' => $order->id, // [cite: 790]
                    'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT), // [cite: 791]
                    'redirect' => route('orders.index'), // [cite: 792]
                    'payment_method' => $validated['payment_method'], // [cite: 793]
                ]); // [cite: 787, 794]
            }

            return redirect()
                ->route('orders.index') // [cite: 797]
                ->with('success', 'Pesanan berhasil dibuat! Anda dapat melihat status pesanan di halaman ini.'); // [cite: 798]

        } catch (\Exception $e) {
            // Logika catch untuk JSON atau redirect back
            if ($request->wantsJson()) {
                return response()->json([
                    'error' => $e->getMessage() // Mengembalikan pesan error (termasuk stok tidak cukup)
                ], 500); // [cite: 801-803]
            }
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan: ' . $e->getMessage()); // [cite: 805]
        }
    }
}