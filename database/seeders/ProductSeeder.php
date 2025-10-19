<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories for foreign key references
        $mensClothing = Category::where('slug', 'mens-clothing')->first();
        $womensClothing = Category::where('slug', 'womens-clothing')->first();
        $accessories = Category::where('slug', 'accessories')->first();
        $footwear = Category::where('slug', 'footwear')->first();
        $sportswear = Category::where('slug', 'sportswear')->first();

        $products = [
            // Men's Clothing
            [
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-t-shirt',
                'description' => 'Comfortable and breathable cotton t-shirt perfect for everyday wear.',
                'short_description' => 'Classic cotton tee for everyday comfort',
                'sku' => 'MCT001',
                'price' => 29.99,
                'sale_price' => 24.99,
                'stock_quantity' => 150,
                'category_id' => $mensClothing->id,
                'images' => [
                    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400',
                    'https://images.unsplash.com/photo-1503341504253-dff4815485f1?w=400'
                ],
                'attributes' => [
                    'material' => 'Cotton',
                    'fit' => 'Regular',
                    'care' => 'Machine wash',
                    'sizes' => ['S', 'M', 'L', 'XL'],
                    'colors' => ['White', 'Black', 'Navy', 'Gray']
                ],
                'weight' => 0.2,
                'dimensions' => '70x50x1 cm',
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Denim Jeans',
                'slug' => 'denim-jeans',
                'description' => 'Premium quality denim jeans with a modern fit and classic style.',
                'short_description' => 'Premium denim jeans with modern fit',
                'sku' => 'MDJ002',
                'price' => 89.99,
                'sale_price' => null,
                'stock_quantity' => 75,
                'category_id' => $mensClothing->id,
                'images' => [
                    'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400'
                ],
                'attributes' => [
                    'material' => 'Denim',
                    'fit' => 'Slim',
                    'care' => 'Machine wash cold',
                    'sizes' => ['28', '30', '32', '34', '36'],
                    'colors' => ['Blue', 'Black', 'Light Blue']
                ],
                'weight' => 0.8,
                'dimensions' => '110x40x2 cm',
                'is_featured' => false,
                'is_active' => true,
            ],

            // Women's Clothing
            [
                'name' => 'Floral Summer Dress',
                'slug' => 'floral-summer-dress',
                'description' => 'Beautiful floral print dress perfect for summer occasions and casual outings.',
                'short_description' => 'Beautiful floral dress for summer',
                'sku' => 'WFD003',
                'price' => 79.99,
                'sale_price' => 59.99,
                'stock_quantity' => 60,
                'category_id' => $womensClothing->id,
                'images' => [
                    'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=400',
                    'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400'
                ],
                'attributes' => [
                    'material' => 'Polyester blend',
                    'fit' => 'A-line',
                    'care' => 'Hand wash recommended',
                    'sizes' => ['XS', 'S', 'M', 'L', 'XL'],
                    'colors' => ['Pink Floral', 'Blue Floral', 'White Floral']
                ],
                'weight' => 0.3,
                'dimensions' => '90x45x1 cm',
                'is_featured' => true,
                'is_active' => true,
            ],

            // Accessories
            [
                'name' => 'Leather Wallet',
                'slug' => 'leather-wallet',
                'description' => 'Genuine leather wallet with multiple card slots and bill compartments.',
                'short_description' => 'Genuine leather wallet with card slots',
                'sku' => 'ALW004',
                'price' => 49.99,
                'sale_price' => null,
                'stock_quantity' => 100,
                'category_id' => $accessories->id,
                'images' => [
                    'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400'
                ],
                'attributes' => [
                    'material' => 'Genuine leather',
                    'slots' => '8 card slots',
                    'care' => 'Leather conditioner recommended',
                    'colors' => ['Black', 'Brown', 'Tan']
                ],
                'weight' => 0.1,
                'dimensions' => '11x9x2 cm',
                'is_featured' => false,
                'is_active' => true,
            ],

            // Footwear
            [
                'name' => 'Running Sneakers',
                'slug' => 'running-sneakers',
                'description' => 'Comfortable running sneakers with advanced cushioning and breathable mesh upper.',
                'short_description' => 'Comfortable running sneakers with cushioning',
                'sku' => 'FRS005',
                'price' => 129.99,
                'sale_price' => 99.99,
                'stock_quantity' => 80,
                'category_id' => $footwear->id,
                'images' => [
                    'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400',
                    'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400'
                ],
                'attributes' => [
                    'material' => 'Mesh and synthetic',
                    'sole' => 'Rubber',
                    'care' => 'Wipe clean with damp cloth',
                    'sizes' => ['7', '8', '9', '10', '11', '12'],
                    'colors' => ['White', 'Black', 'Gray', 'Blue']
                ],
                'weight' => 0.6,
                'dimensions' => '30x12x10 cm',
                'is_featured' => true,
                'is_active' => true,
            ],

            // Sportswear
            [
                'name' => 'Athletic Shorts',
                'slug' => 'athletic-shorts',
                'description' => 'Lightweight athletic shorts with moisture-wicking fabric for optimal performance.',
                'short_description' => 'Lightweight athletic shorts with moisture-wicking',
                'sku' => 'SAS006',
                'price' => 39.99,
                'sale_price' => null,
                'stock_quantity' => 120,
                'category_id' => $sportswear->id,
                'images' => [
                    'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400'
                ],
                'attributes' => [
                    'material' => 'Polyester blend',
                    'features' => 'Moisture-wicking',
                    'care' => 'Machine wash cold',
                    'sizes' => ['S', 'M', 'L', 'XL'],
                    'colors' => ['Black', 'Navy', 'Gray', 'Red']
                ],
                'weight' => 0.15,
                'dimensions' => '40x35x1 cm',
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
