<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order; // Asumsi kamu punya model Order
use App\Models\User; // Asumsi kamu punya model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

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
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'is_active' => 'required|boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi 'image_file'
        ]);

        // 2. Ambil atau Buat Model Product
        $produk = $request->id ? Product::find($request->id) : new Product;

        // 3. Proses upload IMAGE
        // Ambil path lama (disimpan di kolom 'image' DB)
        $path_gambar = $produk->image ?? null; 

        // PERBAIKAN KRUSIAL A: Cek file yang diupload bernama 'image_file'
        if ($request->hasFile('image_file')) { 
            
            // Hapus image lama jika ada (optional, tapi disarankan)
            // Catatan: Ini mengasumsikan file lama disimpan di public/uploads/products/
            if ($produk->image && file_exists(public_path($produk->image))) {
                unlink(public_path($produk->image));
            }

            // PERBAIKAN KRUSIAL B: Mengambil file yang bernama 'image_file'
            $file = $request->file('image_file'); 
            $extension = $file->getClientOriginalExtension();
            $fileName = 'uploads/products/' . time() . '_' . uniqid() . '.' . $extension; // Pastikan path konsisten

            // Pindahkan file ke public/uploads/products/
            $file->move(public_path('uploads/products'), basename($fileName)); 
            
            // **SIMPAN PATH RELATIF** dari folder public ke database
            $path_gambar = $fileName; 
        }

        // 4. Simpan data produk
        // PERBAIKAN KRUSIAL C: Menggunakan nama field yang benar dari Request
        $produk->name = $request->name;       
        $produk->category_id = $request->category_id; // Tambahkan category_id
        $produk->price = $request->price;     
        $produk->stock = $request->stock;     
        $produk->is_active = $request->is_active; // Tambahkan is_active
        $produk->image = $path_gambar;        // Menyimpan path ke kolom 'image' di database

        $produk->save();

        return response()->json(['message' => 'Produk berhasil disimpan!']);
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