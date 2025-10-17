<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Order;       
use App\Models\OrderItem;   
use App\Models\Product;     
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;  
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log; 

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

        $deliveryFee = 15000; // Contoh statis
        $subtotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $total = $subtotal + $deliveryFee;

        return view('pages.checkout', compact('cartItems', 'subtotal', 'deliveryFee', 'total'));
    }
    
    public function processOrder(Request $request): RedirectResponse
    {
        $user = Auth::user();
        
        // 1. Validasi Input
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'postal_code' => 'required|string|size:5',
            'payment_method' => 'required|in:cod,transfer,qris',
            'order_notes' => 'nullable|string',
        ]);

        $userId = $user->id;
        $cartItems = CartItem::with('product')->where('user_id', $userId)->get();
        $deliveryFee = 15000;
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong saat proses checkout.');
        }

        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $addressText = implode(', ', [
                $validated['shipping_address'],
                $validated['city'],
                $validated['province'],
                $validated['postal_code']
            ]);
            
            // Cek Stok dan Hitung Total
            foreach ($cartItems as $item) {
                $product = $item->product;
                $quantity = $item->quantity;

                if (!$product || $product->stock < $quantity) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', 'Stok produk ' . ($product->name ?? 'unknown') . ' tidak cukup.');
                }
                $totalAmount += $product->price * $quantity;
            }
            
            $grandTotal = $totalAmount + $deliveryFee;

            // 2. Buat Entri Order
            $order = Order::create([
                'user_id' => $userId,
                'total' => $grandTotal,
                'status' => 'pending', // Status awal
                'address_text' => $addressText,
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'payment_method' => $validated['payment_method'],
                'order_notes' => $validated['order_notes'],
            ]);

            // 3. Pindahkan CartItems ke OrderItems dan Kurangi Stok
            foreach ($cartItems as $item) {
                $product = $item->product;
                $quantity = $item->quantity;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'qty' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ]);

                // Kurangi Stok
                $product->stock -= $quantity;
                $product->save();
            }

            // 4. Hapus Item dari Keranjang
            CartItem::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Pesanan Anda berhasil dibuat! (No. Order: ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Failed: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'Gagal memproses pesanan. Error internal. ' . $e->getMessage());
        }
    }
}