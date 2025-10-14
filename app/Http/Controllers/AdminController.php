<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk file management

class AdminController extends Controller
{
    /**
     * Mengambil statistik utama untuk dashboard.
     */
    public function getDashboardStats()
    {
        return response()->json([
            'total_products' => Product::count(),
            'new_orders'     => Order::whereIn('status', ['pending', 'diproses'])->count(),
            'total_users'    => User::count(),
            'total_sales'    => Order::where('status', 'selesai')->sum('total'),
        ]);
    }

    /**
     * Mengambil daftar produk dengan filter.
     */
    public function getProducts(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('query')) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        return response()->json($query->get());
    }

    /**
     * Mengambil detail satu produk untuk form edit.
     */
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
    
    /**
     * Menyimpan produk baru atau memperbarui produk yang ada.
     */
    public function saveProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);
    
        try {
            DB::beginTransaction();
    
            $product = Product::findOrNew($request->input('id'));
            
            // Map the validated data to correct field names
            $product->name = $validated['name'];
            $product->category_id = $validated['category_id'];
            $product->price = $validated['price'];
            $product->stock_quantity = $validated['stock']; // Map 'stock' to 'stock_quantity'
            $product->description = $validated['description'];
            $product->is_active = $validated['is_active'];
            
            // Generate SKU if it's a new product
            if (!$product->exists && empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(uniqid());
            }
    
            if ($request->hasFile('image_file')) {
            // Hapus gambar lama jika ada
            $currentImages = $product->images ?? [];
            if (!empty($currentImages)) {
                foreach ($currentImages as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            // Simpan gambar baru
            $path = $request->file('image_file')->store('products', 'public');
            $product->images = [$path]; // Store as array since it's JSON column
        }
    
            $product->save();
            DB::commit();
    
            return response()->json(['message' => 'Produk berhasil disimpan!']);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus produk.
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->images && !empty($product->images)) {
            // Decode JSON string to array if needed
            $images = is_string($product->images) ? json_decode($product->images, true) : $product->images;
            
            if (is_array($images)) {
                foreach ($images as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }
        }

        $product->delete();
        return response()->json(['message' => 'Produk berhasil dihapus!']);
    }

    /**
     * Mengambil semua kategori (untuk dropdown dan halaman kategori).
     */
    public function getCategories()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return response()->json($categories);
    }

    /**
     * Mengambil detail satu kategori untuk form edit.
     */
    public function getCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    /**
     * Menyimpan atau memperbarui kategori.
     */
    public function saveCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $request->input('id'),
        ]);
        
        Category::updateOrCreate(['id' => $request->input('id')], ['name' => $validated['name']]);
        
        $message = $request->input('id') ? 'Kategori berhasil diupdate!' : 'Kategori berhasil ditambahkan!';
        return response()->json(['message' => $message]);
    }

    /**
     * Menghapus kategori.
     */
    public function deleteCategory($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        
        if ($category->products_count > 0) {
            return response()->json(['message' => 'Tidak bisa menghapus, kategori ini masih memiliki produk.'], 409);
        }
        
        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus!']);
    }

    /**
     * Mengambil daftar pesanan.
     */
    public function getOrders()
    {
        $orders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->get()
            ->map(function($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'user' => $order->user,
                    'total' => $order->total,
                    'status' => $order->status,
                    'payment_method' => $order->payment_method,
                    'order_notes' => $order->order_notes,
                    'address_text' => $order->address_text,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                    'items_count' => $order->orderItems->count(),
                    'items' => $order->orderItems->map(function($item) {
                        return [
                            'product_name' => $item->product->name,
                            'qty' => $item->qty,
                            'price' => $item->price,
                            'subtotal' => $item->subtotal
                        ];
                    })
                ];
            });
        return response()->json($orders);
    }

    /**
     * Memperbarui status pesanan.
     */
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|in:pending,diproses,dikirim,selesai,batal',
        ]);

        $order = Order::findOrFail($request->input('id'));
        $newStatus = $request->input('status');
        
        // Validasi transisi status yang logis
        $validTransitions = [
            'pending' => ['diproses', 'batal'],
            'diproses' => ['dikirim', 'batal'],
            'dikirim' => ['selesai'],
            'selesai' => [], // Status final, tidak bisa diubah
            'batal' => [] // Status final, tidak bisa diubah
        ];
        
        if (!in_array($newStatus, $validTransitions[$order->status] ?? [])) {
            return response()->json([
                'message' => 'Transisi status tidak valid dari ' . $order->status . ' ke ' . $newStatus
            ], 422);
        }

        $order->status = $newStatus;
        $order->save();

        return response()->json(['message' => 'Status pesanan berhasil diupdate!']);
    }
}