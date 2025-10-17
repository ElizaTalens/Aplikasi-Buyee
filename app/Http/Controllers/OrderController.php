<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman status pesanan.
     */
    public function index()
    {
        $dbOrders = Order::with(['orderItems.product'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        $orders = $dbOrders->map(function (Order $order) {
            
            // Kalkulasi subtotal produk dari order items (sebagai basis kalkulasi)
            $subtotalProduct = $order->orderItems->sum('subtotal');
            $deliveryFee = $order->total - $subtotalProduct; 

            return [
                'id' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'raw_id' => $order->id,
                'date' => $order->created_at->format('d F Y, H:i') . ' WIB',
                'status' => $order->status,
                'total' => number_format($order->total, 0, ',', '.'),
                'total_value' => (int) $order->total,
                'customer_name' => $order->customer_name ?? Auth::user()->name,
                'customer_email' => $order->customer_email ?? Auth::user()->email,
                'customer_phone' => $order->customer_phone ?? '-',
                'payment_method' => $order->payment_method ?? 'COD',
                'order_notes' => $order->order_notes,
                'address_text' => $order->address_text,
                'items' => $order->orderItems->map(function ($item) {
                    $firstImage = $item->product->image ?? null; 
                    
                    return [
                        'name' => $item->product->name,
                        'quantity' => $item->qty,
                        'price' => number_format($item->price, 0, ',', '.'),
                        'price_value' => (int) $item->price,
                        'subtotal' => number_format($item->subtotal, 0, ',', '.'),
                        'subtotal_value' => (int) $item->subtotal, // Penting untuk kalkulasi di view
                        'image' => $firstImage ? ('/' . ltrim($firstImage, '/')) : asset('images/placeholder.jpg'), 
                        'category_icon' => $this->getProductIcon($item->product->category_id),
                    ];
                })->toArray(),
            ];
        })->toArray();

        return view('pages.riwayatPesanan', [
            'orders' => $orders,
        ]);
    }

    /**
     * Mengembalikan detail pesanan (JSON).
     */
    public function show(Order $order): JsonResponse
    {
        $this->authorizeOrder($order);

        $order->load(['orderItems.product']);
        
        $subtotalProduct = $order->orderItems->sum('subtotal');
        $deliveryFee = $order->total - $subtotalProduct;

        $payload = [
            'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'status' => $order->status,
            'total' => number_format($order->total, 0, ',', '.'),
            'total_value' => (int) $order->total,
            'subtotal_product' => number_format($subtotalProduct, 0, ',', '.'),
            'delivery_fee' => number_format($deliveryFee, 0, ',', '.'),
            'created_at' => $order->created_at->format('d F Y, H:i') . ' WIB',
            'date' => $order->created_at->format('d F Y, H:i') . ' WIB',
            'payment_method' => $order->payment_method ?? 'COD',
            'customer_name' => $order->customer_name ?? Auth::user()->name,
            'customer_email' => $order->customer_email ?? Auth::user()->email,
            'customer_phone' => $order->customer_phone ?? '-',
            'address_text' => $order->address_text,
            'order_notes' => $order->order_notes,
            'items' => $order->orderItems->map(function ($item) {
                $firstImage = $item->product->image ?? null; 
                
                return [
                    'name' => $item->product->name,
                    'quantity' => $item->qty,
                    'price' => number_format($item->price, 0, ',', '.'),
                    'subtotal' => number_format($item->subtotal, 0, ',', '.'),
                    'image' => $firstImage ? ('/' . ltrim($firstImage, '/')) : asset('images/placeholder.jpg'),
                    'category_icon' => $this->getProductIcon($item->product->category_id),
                ];
            })->toArray(),
        ];

        return response()->json($payload);
    }

    /**
     * Membatalkan pesanan milik user (status pending saja).
     */
    public function cancel(Order $order): JsonResponse
    {
        $this->authorizeOrder($order);

        if ($order->status !== 'pending' && $order->status !== 'diproses') {
            return response()->json([
                'message' => 'Pesanan tidak dapat dibatalkan karena statusnya sudah ' . $order->status . '.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        DB::beginTransaction();
        try {
            // 1. Kembalikan Stok Produk
            foreach ($order->orderItems as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->stock += $item->qty;
                    $product->save();
                }
            }

            // 2. Update Status Order
            $order->update(['status' => 'batal']);
            
            DB::commit();

            return response()->json([
                'message' => 'Pesanan berhasil dibatalkan dan stok dikembalikan.',
                'status' => 'batal',
                'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Cancel Order Failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal membatalkan pesanan. Error internal.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Helper icon kategori.
     */
    private function getProductIcon($categoryId)
    {
        $icons = [
            1 => 'fa-tshirt', // Fashion
            2 => 'fa-laptop', // Elektronik
            3 => 'fa-home',   // Home & Living
            4 => 'fa-heart',  // Kecantikan
            5 => 'fa-gamepad',// Mainan/Game
        ];

        return $icons[$categoryId] ?? 'fa-box';
    }

    private function authorizeOrder(Order $order): void
    {
        if ($order->user_id !== Auth::id()) {
            abort(Response::HTTP_FORBIDDEN, 'Anda tidak memiliki akses ke pesanan ini.');
        }
    }
}