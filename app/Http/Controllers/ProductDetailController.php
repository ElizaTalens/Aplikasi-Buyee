<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show()
    {
        // sementara masih statis
        $colors = [
            ['code' => 'brown', 'hex' => '#5d4e33'],
            ['code' => 'green', 'hex' => '#3a564b'],
            ['code' => 'navy',  'hex' => '#2f3f59'],
        ];
        $sizes = [
            ['code' => 'S',  'label' => 'Small'],
            ['code' => 'M',  'label' => 'Medium'],
            ['code' => 'L',  'label' => 'Large'],
            ['code' => 'XL', 'label' => 'X-Large'],
        ];
        $selectedColor = 'brown';
        $selectedSize  = 'L';

        return view('pages.product-tee', compact('colors','sizes','selectedColor','selectedSize'));
    }
}
