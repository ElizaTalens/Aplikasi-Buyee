<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Men\'s Clothing',
                'slug' => 'mens-clothing',
                'description' => 'Stylish and comfortable clothing for men',
                'image_url' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Women\'s Clothing',
                'slug' => 'womens-clothing',
                'description' => 'Trendy and fashionable clothing for women',
                'image_url' => 'https://images.unsplash.com/photo-1445205170230-053b83016050?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Complete your look with our accessories collection',
                'image_url' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Footwear',
                'slug' => 'footwear',
                'description' => 'Comfortable and stylish shoes for every occasion',
                'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400',
                'is_active' => true,
            ],
            [
                'name' => 'Sportswear',
                'slug' => 'sportswear',
                'description' => 'Athletic wear for active lifestyles',
                'image_url' => 'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
