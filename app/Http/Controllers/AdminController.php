<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order; // Asumsi kamu punya model Order
use App\Models\User; // Asumsi kamu punya model User
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Stats
    public function getDashboardStats()
    {
        $totalProducts = Product::count();
        $newOrders = Order::where('status', 'diproses')->count();
        $totalUsers = User::count();
        $totalSales = Order::where('status', 'selesai')->sum('total');
        
        return response()->json([
            'total_products' => $totalProducts,
            'new_orders' => $newOrders,
            'total_users' => $totalUsers,
            'total_sales' => $totalSales,
        ]);
    }

    // Products Management
    // app/Http/Controllers/Admin/AdminController.php

// ... (bagian atas controller)

    public function getProducts(Request $request)
    {
        // Mengambil semua produk dengan relasi kategori
        $products = Product::with('category');

        // Menerapkan filter pencarian jika ada 'query'
        if ($request->has('query')) {
            $query = $request->input('query');
            $products->where('name', 'like', '%' . $query . '%');
        }

        // Menerapkan filter kategori jika ada 'category_id'
        if ($request->has('category_id') && $request->input('category_id') != '') {
            $products->where('category_id', $request->input('category_id'));
        }

        return response()->json($products->get());
    }

    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function saveProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'is_active' => 'required|boolean',
        ]);

        $productId = $request->input('id');
        if ($productId) {
            $product = Product::findOrFail($productId);
            $product->update($request->all());
            return response()->json(['message' => 'Produk berhasil diupdate!']);
        } else {
            Product::create($request->all());
            return response()->json(['message' => 'Produk berhasil ditambahkan!']);
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus!']);
    }

    // Categories Management
    public function getCategories()
    {
        $categories = Category::withCount('products')->get();
        return response()->json($categories);
    }

    public function getCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    public function saveCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $request->input('id'),
        ]);
        
        $categoryId = $request->input('id');
        if ($categoryId) {
            $category = Category::findOrFail($categoryId);
            $category->update($request->all());
            return response()->json(['message' => 'Kategori berhasil diupdate!']);
        } else {
            Category::create($request->all());
            return response()->json(['message' => 'Kategori berhasil ditambahkan!']);
        }
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->products()->count() > 0) {
            return response()->json(['message' => 'Tidak bisa menghapus kategori yang masih memiliki produk.'], 409);
        }
        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus!']);
    }

    // Orders Management
    public function getOrders()
    {
        $orders = Order::with('user')->get();
        return response()->json($orders);
    }

    public function updateOrderStatus(Request $request)
    {
        $order = Order::findOrFail($request->input('id'));
        $order->status = $request->input('status');
        $order->save();

        return response()->json(['message' => 'Status pesanan berhasil diupdate!']);
    }
}