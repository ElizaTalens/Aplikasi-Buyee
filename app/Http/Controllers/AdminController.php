<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // statistik dashboard
    public function getDashboardStats()
    {
        return response()->json([
            'total_products' => Product::count(),
            'new_orders'     => Order::whereIn('status', ['pending', 'diproses'])->count(), 
            'total_users'    => User::count(),
            'total_sales'    => Order::where('status', 'selesai')->sum('total'),
        ]);
    }

    //daftar produk dengan filter pencarian dan kategori
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

    // satu produk untuk form edit
    public function getProduct($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }
    
    // menyimpan atau memperbarui produk
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
            $product->name = $validated['name'];
            $product->category_id = $validated['category_id'];
            $product->price = $validated['price'];
            $product->stock_quantity = $validated['stock']; 
            $product->description = $validated['description'];
            $product->is_active = $validated['is_active'];
            
            
            if (!$product->exists && empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(6));
            }
    
            if ($request->hasFile('image_file')) {
                $currentImages = $product->images ?? [];
                if (!empty($currentImages)) {
                    $images = is_string($currentImages) ? json_decode($currentImages, true) : $currentImages;
                    if (is_array($images)) {
                        foreach ($images as $imagePath) {
                            if (Storage::disk('public')->exists($imagePath)) {
                                Storage::disk('public')->delete($imagePath);
                            }
                        }
                    }
                }

                $path = $request->file('image_file')->store('products', 'public');
                $product->images = [$path]; 
            } else if ($product->exists && !$request->hasFile('image_file')) {
                // Jika tidak ada file baru, biarkan gambar lama tetap ada
            }
    
            $product->save();
            DB::commit();
    
            return response()->json(['message' => 'Produk berhasil disimpan!']);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    // hapus produk
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);

        if ($product->images && !empty($product->images)) {
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

    // kategori
    public function getCategories()
    {
        $categories = Category::withCount('products')->orderBy('name')->get();
        return response()->json($categories);
    }

    // satu kategori untuk form edit
    public function getCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // memperbarui kategori 
    public function saveCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $request->input('id'),
        ]);
        
        $data = ['name' => $validated['name']];
        $data['slug'] = Str::slug($validated['name']); 

        try {
            Category::updateOrCreate(['id' => $request->input('id')], $data);
            
            $message = $request->input('id') ? 'Kategori berhasil diupdate!' : 'Kategori berhasil ditambahkan!';
            return response()->json(['message' => $message]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan kategori: ' . $e->getMessage()], 500);
        }
    }

    // hapus kategori
    public function deleteCategory($id)
    {
        $category = Category::withCount('products')->findOrFail($id);
        
        if ($category->products_count > 0) {
            return response()->json(['message' => 'Tidak bisa menghapus, kategori ini masih memiliki produk.'], 409);
        }
        
        $category->delete();
        return response()->json(['message' => 'Kategori berhasil dihapus!']);
    }

    // daftar pesanan dengan filter pencarian dan status
    public function getOrders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->latest();

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('customer_name', 'like', $searchTerm)
                  ->orWhere('customer_email', 'like', $searchTerm) 
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        if ($request->filled('status') && $request->input('status') !== 'Semua Status') {
            $query->where('status', strtolower($request->input('status')));
        }

        $orders = $query->get()
            ->map(function($order) {
                $items = $order->orderItems ? $order->orderItems->map(function($item) {
                    return [
                        'product_name' => $item->product->name ?? 'Produk Dihapus',
                        'qty' => $item->qty,
                        'price' => $item->price,
                        'subtotal' => $item->subtotal
                    ];
                }) : collect();
                
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
                    'items_count' => $items->count(),
                    'items' => $items
                ];
            });
        return response()->json($orders);
    }

    // perbarui status pesanan
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            'status' => 'required|in:pending,diproses,dikirim,selesai,batal', 
        ]);

        $order = Order::findOrFail($request->input('id'));
        $newStatus = $request->input('status');
        
        $validTransitions = [
            'pending' => ['diproses', 'batal'],
            'diproses' => ['dikirim', 'batal'],
            'dikirim' => ['selesai'],
            'selesai' => [], 
            'batal' => [] 
        ];
        
        if ($newStatus !== $order->status && !in_array($newStatus, $validTransitions[$order->status] ?? [])) {
            return response()->json([
                'message' => 'Transisi status tidak valid dari ' . $order->status . ' ke ' . $newStatus
            ], 422);
        }

        $order->status = $newStatus;
        $order->save();

        return response()->json(['message' => 'Status pesanan berhasil diupdate!']);
    }
}