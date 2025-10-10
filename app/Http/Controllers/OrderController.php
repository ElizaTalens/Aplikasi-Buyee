<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan halaman status pesanan.
     */
    public function index()
    {
        // --- Simulasi Data dari Database ---
        // Nantinya, Anda akan mengambil data ini dari database, bukan ditulis manual seperti ini.
        $orders = [
            [
                'id' => 'ORD-2025-001',
                'date' => '20 September 2025, 14:30 WIB',
                'status' => 'dikirim', // Pilihan: 'diproses', 'dikirim', 'selesai', 'batal'
                'total' => '165.000',
                'items' => [
                    [
                        'name' => 'Kaos Polos Premium Cotton',
                        'quantity' => 2,
                        'price' => '75.000',
                        'subtotal' => '150.000',
                        'icon' => 'fa-tshirt'
                    ]
                ]
            ],
            [
                'id' => 'ORD-2025-002',
                'date' => '22 September 2025, 10:15 WIB',
                'status' => 'diproses',
                'total' => '270.000',
                'items' => [
                    [
                        'name' => 'Sepatu Sneakers Premium',
                        'quantity' => 1,
                        'price' => '250.000',
                        'subtotal' => '250.000',
                        'icon' => 'fa-running'
                    ]
                ]
            ],
            [
                'id' => 'ORD-2025-003',
                'date' => '15 September 2025, 16:20 WIB',
                'status' => 'selesai',
                'total' => '90.000',
                'items' => [
                    [
                        'name' => 'Case HP Anti-Crack',
                        'quantity' => 3,
                        'price' => '25.000',
                        'subtotal' => '75.000',
                        'icon' => 'fa-mobile-alt'
                    ]
                ]
            ]
        ];
        // --- Akhir Simulasi Data ---

        // 1. Panggil file view bernama 'status_pesanan.blade.php'
        // 2. Kirim data '$orders' ke dalam view agar bisa ditampilkan
        return view('pages.riwayatPesanan', ['ordersData' => $orders]);
    }
}