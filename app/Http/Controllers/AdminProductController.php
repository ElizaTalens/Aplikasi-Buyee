<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product; // Pastikan Anda mengimpor Model Produk Anda
use Illuminate\Support\Facades\Storage;

// ... di dalam kelas AdminProductController
class ProductController extends Controller
{
    public function save(Request $request)
    {
        // 1. Validasi Input (termasuk validasi file)
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // JPEG, PNG, JPG, GIF, WebP diizinkan, Max 2MB
        ]);

        // 2. Tentukan apakah ini operasi CREATE atau UPDATE
        $product = $request->id ? Product::find($request->id) : new Product();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }
        
        // Inisialisasi variabel untuk nama gambar lama (jika ada)
        $oldImages = $product->images ?? []; 

        // 3. Tangani File Upload
        if ($request->hasFile('image_file')) {
            // Hapus gambar lama sebelum menyimpan yang baru
            if (!empty($oldImages)) {
                foreach ($oldImages as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            // Simpan file baru
            $file = $request->file('image_file');
            $imageName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            // Simpan file ke storage/app/public/products/
            $file->storeAs('products', $imageName, 'public');

            // Set nama file untuk disimpan di database sebagai array
            $product->images = ['products/' . $imageName]; 

        } elseif ($request->id && !$request->hasFile('image_file') && !empty($oldImages)) {
            // Jika UPDATE dan tidak ada file baru diupload, 
            // pastikan field images tidak berubah
            // (kode ini tidak diperlukan jika Anda hanya set $product->images di atas)
        }

        // 4. Update/Simpan data non-file ke database
        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->stock_quantity = $request->stock;
        $product->is_active = $request->is_active;

        $product->save();

        return response()->json(['message' => 'Produk berhasil disimpan!', 'product' => $product], 200);
    }
}