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
    /**
     * Mengambil statistik utama untuk dashboard.
     */
    public function getDashboardStats()
    {
        return response()->json([
            'total_products' => Product::count(),
            // Mengubah 'pending' dan 'diproses' sesuai yang disarankan oleh AdminController lama
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
        // Pastikan relasi 'category' dimuat
        $query = Product::with('category')->latest();

        if ($request->filled('query')) {
            $query->where('name', 'like', '%' . $request->input('query') . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        // PERHATIAN: Perlu memastikan properti yang dikembalikan cocok
        // dengan apa yang diharapkan di JS (stock_quantity vs stock, images vs image)
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
            // Menggunakan stock_quantity untuk kompatibilitas DB yang lebih baik
            $product->stock_quantity = $validated['stock']; 
            $product->description = $validated['description'];
            $product->is_active = $validated['is_active'];
            
            // Generate SKU if it's a new product
            if (!$product->exists && empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(6));
            }
    
            if ($request->hasFile('image_file')) {
                // Hapus gambar lama jika ada
                $currentImages = $product->images ?? [];
                // Asumsi $product->images adalah JSON array of paths
                if (!empty($currentImages)) {
                    $images = is_string($currentImages) ? json_decode($currentImages, true) : $currentImages;
                    if (is_array($images)) {
                        foreach ($images as $imagePath) {
                            // Path yang disimpan di DB adalah 'products/namafile.jpg'
                            if (Storage::disk('public')->exists($imagePath)) {
                                Storage::disk('public')->delete($imagePath);
                            }
                        }
                    }
                }

                // Simpan gambar baru
                $path = $request->file('image_file')->store('products', 'public');
                // Simpan sebagai array JSON untuk kolom 'images'
                $product->images = [$path]; 
            } else if ($product->exists && !$request->hasFile('image_file')) {
                 // JANGAN HAPUS data gambar jika update tanpa upload file baru
                 // Pastikan 'images' tidak ditimpa jika field tidak di-fill
                 // Ini sudah ditangani oleh tidak adanya set $product->images di luar blok if
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
        
        $data = ['name' => $validated['name']];
        // Tambahkan slug karena ini praktik yang baik
        $data['slug'] = Str::slug($validated['name']); 

        try {
            Category::updateOrCreate(['id' => $request->input('id')], $data);
            
            $message = $request->input('id') ? 'Kategori berhasil diupdate!' : 'Kategori berhasil ditambahkan!';
            return response()->json(['message' => $message]);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan saat menyimpan kategori: ' . $e->getMessage()], 500);
        }
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
     * Mengambil daftar pesanan dengan filter Pencarian dan Status.
     */
    public function getOrders(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product'])
            ->latest();

        // FILTER PENCARIAN (Berdasarkan Pelanggan, Email)
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->input('search') . '%';
            $query->where(function($q) use ($searchTerm) {
                // Cari berdasarkan Nama Pelanggan (customer_name)
                $q->where('customer_name', 'like', $searchTerm)
                  // Cari berdasarkan Email (customer_email)
                  ->orWhere('customer_email', 'like', $searchTerm) 
                  // Cari di tabel user jika ada relasi
                  ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                  });
            });
        }

        // FILTER STATUS
        if ($request->filled('status') && $request->input('status') !== 'Semua Status') {
            // Perhatikan status yang disimpan di DB: 'Diproses' vs 'diproses'
            // Kita asumsikan status di DB adalah lowercase: 'diproses', 'dikirim', dll.
            $query->where('status', strtolower($request->input('status')));
        }

        $orders = $query->get()
            ->map(function($order) {
                // Menghindari error jika orderItems atau product tidak ada
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
    
    /**
     * Memperbarui status pesanan.
     */
    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:orders,id',
            // Menambahkan 'pending' karena digunakan di JS untuk default/dashboard
            'status' => 'required|in:pending,diproses,dikirim,selesai,batal', 
        ]);

        $order = Order::findOrFail($request->input('id'));
        $newStatus = $request->input('status');
        
        // Validasi transisi status yang logis (seperti di kode lama)
        $validTransitions = [
            'pending' => ['diproses', 'batal'],
            'diproses' => ['dikirim', 'batal'],
            'dikirim' => ['selesai'],
            'selesai' => [], 
            'batal' => [] 
        ];
        
        // Allow re-setting to the current status to bypass the transition check if needed, 
        // but for strict logic, the original check is better.
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