<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            return [
                'id' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'raw_id' => $order->id,
                'date' => $order->created_at->format('d F Y, H:i') . ' WIB',
                'status' => $order->status,
                'total' => number_format($order->total, 0, ',', '.'),
                'total_value' => (int) $order->total,
                'customer_name' => $order->customer_name,
                'customer_email' => $order->customer_email,
                'customer_phone' => $order->customer_phone,
                'payment_method' => $order->payment_method,
                'order_notes' => $order->order_notes,
                'address_text' => $order->address_text,
                'items' => $order->orderItems->map(function ($item) {
                    $images = $item->product->images ?? [];
                    if (is_string($images)) {
                        $decoded = json_decode($images, true);
                        $images = is_array($decoded) ? $decoded : [];
                    }
                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;

                    return [
                        'name' => $item->product->name,
                        'quantity' => $item->qty,
                        'price' => number_format($item->price, 0, ',', '.'),
                        'price_value' => (int) $item->price,
                        'subtotal' => number_format($item->subtotal, 0, ',', '.'),
                        'subtotal_value' => (int) $item->subtotal,
                        'category_icon' => $this->getProductIcon($item->product->category_id),
                        'image' => $firstImage ? ('storage/' . ltrim($firstImage, '/')) : null,
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

        $payload = [
            'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
            'status' => $order->status,
            'total' => number_format($order->total, 0, ',', '.'),
            'total_value' => (int) $order->total,
            'created_at' => $order->created_at->format('d F Y, H:i') . ' WIB',
            'date' => $order->created_at->format('d F Y, H:i') . ' WIB',
            'payment_method' => $order->payment_method,
            'customer_name' => $order->customer_name,
            'customer_email' => $order->customer_email,
            'customer_phone' => $order->customer_phone,
            'address_text' => $order->address_text,
            'order_notes' => $order->order_notes,
            'items' => $order->orderItems->map(function ($item) {
                $images = $item->product->images ?? [];
                if (is_string($images)) {
                    $decoded = json_decode($images, true);
                    $images = is_array($decoded) ? $decoded : [];
                }
                $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;

                return [
                    'name' => $item->product->name,
                    'quantity' => $item->qty,
                    'price' => number_format($item->price, 0, ',', '.'),
                    'subtotal' => number_format($item->subtotal, 0, ',', '.'),
                    'image' => $firstImage ? asset('storage/' . ltrim($firstImage, '/')) : asset('images/placeholder.jpg'),
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

        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Pesanan tidak dapat dibatalkan.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $order->update([
            'status' => 'batal',
        ]);

        return response()->json([
            'message' => 'Pesanan berhasil dibatalkan.',
            'status' => 'batal',
            'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
        ]);
    }

    /**
     * Helper icon kategori.
     */
    private function getProductIcon($categoryId)
    {
        $icons = [
            1 => 'fa-tshirt',
            2 => 'fa-laptop',
            3 => 'fa-home',
            4 => 'fa-heart',
            5 => 'fa-gamepad',
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
