<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and products
        $users = User::where('role', '!=', 'admin')->take(3)->get();
        $products = Product::take(5)->get();

        if ($users->count() == 0 || $products->count() == 0) {
            $this->command->info('No users or products found. Please run UserSeeder and ProductSeeder first.');
            return;
        }

        // Create 10 sample orders
        for ($i = 1; $i <= 10; $i++) {
            $user = $users->random();
            $orderTotal = 0;
            
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => '08' . rand(1000000000, 9999999999),
                'total' => 0, // Will be updated after adding items
                'status' => collect(['pending', 'diproses', 'dikirim', 'selesai'])->random(),
                'payment_method' => collect(['cod', 'transfer', 'qris'])->random(),
                'address_text' => 'Jl. Contoh No. ' . rand(1, 100) . ', Jakarta',
            ]);

            // Add 1-3 random products to each order
            $numItems = rand(1, 3);
            for ($j = 0; $j < $numItems; $j++) {
                $product = $products->random();
                $qty = rand(1, 3);
                $price = $product->price;
                $subtotal = $price * $qty;
                $orderTotal += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $price,
                    'qty' => $qty,
                    'subtotal' => $subtotal,
                ]);
            }

            // Update order total
            $order->update(['total' => $orderTotal]);
        }

        $this->command->info('Created 10 sample orders with items.');
    }
}
