<?php

namespace App\Http\Controllers;
use App\Models\Order;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;   

class OrderController extends Controller
{
    /**
     * Menampilkan halaman status pesanan.
     */
     public function index(): View
    {
        $userId = Auth::id();

        // Mengambil semua pesanan milik user yang sedang login.
        // Eager load orderItems dan produk di dalamnya untuk efisiensi.
        $orders = Order::where('user_id', $userId)
                        ->with(['orderItems.product'])
                        ->latest() // Pesanan terbaru di atas
                        ->get();
                        
        // Mengirim data dinamis ke view
        return view('pages.riwayatPesanan', ['ordersData' => $orders]);
    }
}