<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    public function show(Product $product)
    {
        if (is_string($product->images)) {
            $images = json_decode($product->images, true) ?: [];
        } elseif (is_array($product->images)) {
            $images = $product->images;
        } else {
            $images = $product->images ?? [];
        }

        return view('pages.product-details', [
            'product' => $product,
            'images' => $images,
        ]);
    }
}
