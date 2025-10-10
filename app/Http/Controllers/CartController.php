<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the user's cart
     */
    public function index(): JsonResponse|View
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });

        if (request()->expectsJson()) {
            return response()->json([
                'cart_items' => $cartItems,
                'total' => $total,
                'count' => $cartItems->sum('quantity')
            ]);
        }

        // PERBAIKAN: Mengubah 'cart.index' menjadi 'pages.cart'
        // agar sesuai dengan lokasi file: resources/views/pages/cart.blade.php
        return view('pages.cart', compact('cartItems', 'total'));
    }

    /**
     * Add item to cart
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'product_options' => 'nullable|array'
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check if product is active and in stock
        if (!$product->is_active) {
            return response()->json([
                'message' => 'Product is not available'
            ], 422);
        }

        if ($product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock available'
            ], 422);
        }

        // Check if item already exists in cart
        $existingItem = CartItem::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $validated['quantity'];
            
            if ($product->stock_quantity < $newQuantity) {
                return response()->json([
                    'message' => 'Cannot add more items. Insufficient stock available'
                ], 422);
            }

            $existingItem->update([
                'quantity' => $newQuantity,
                'price' => $product->getFinalPrice(),
                'product_options' => $validated['product_options'] ?? []
            ]);

            $cartItem = $existingItem;
        } else {
            $cartItem = CartItem::create([
                'user_id' => Auth::id(),
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
                'price' => $product->getFinalPrice(),
                'product_options' => $validated['product_options'] ?? []
            ]);
        }

        return response()->json([
            'message' => 'Item added to cart successfully',
            'cart_item' => $cartItem->load('product')
        ], 201);
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = CartItem::where('user_id', Auth::id())
            ->findOrFail($id);

        $product = $cartItem->product;

        if ($product->stock_quantity < $validated['quantity']) {
            return response()->json([
                'message' => 'Insufficient stock available'
            ], 422);
        }

        $cartItem->update([
            'quantity' => $validated['quantity'],
            'price' => $product->getFinalPrice()
        ]);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'cart_item' => $cartItem->load('product')
        ]);
    }

    /**
     * Remove item from cart
     */
    public function destroy(string $id): JsonResponse
    {
        $cartItem = CartItem::where('user_id', Auth::id())
            ->findOrFail($id);

        $cartItem->delete();

        return response()->json([
            'message' => 'Item removed from cart successfully'
        ]);
    }

    /**
     * Clear all items from cart
     */
    public function clear(): JsonResponse
    {
        CartItem::where('user_id', Auth::id())->delete();

        return response()->json([
            'message' => 'Cart cleared successfully'
        ]);
    }

    /**
     * Get cart count
     */
    public function count(): JsonResponse
    {
        $count = CartItem::where('user_id', Auth::id())
            ->sum('quantity');

        return response()->json(['count' => $count]);
    }
}
