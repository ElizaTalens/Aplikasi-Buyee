<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product; 
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'is_active' => 'required|boolean',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', 
        ]);

        $product = $request->id ? Product::find($request->id) : new Product();

        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan.'], 404);
        }
    
        $oldImages = $product->images ?? []; 

        if ($request->hasFile('image_file')) {
            if (!empty($oldImages)) {
                foreach ($oldImages as $imagePath) {
                    if (Storage::disk('public')->exists($imagePath)) {
                        Storage::disk('public')->delete($imagePath);
                    }
                }
            }

            $file = $request->file('image_file');
            $imageName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            $file->storeAs('products', $imageName, 'public');
            $product->images = ['products/' . $imageName]; 

        } elseif ($request->id && !$request->hasFile('image_file') && !empty($oldImages)) {
           //gambar lama tp ada
        }

        $product->name = $request->name;
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->stock_quantity = $request->stock;
        $product->is_active = $request->is_active;

        $product->save();

        return response()->json(['message' => 'Produk berhasil disimpan!', 'product' => $product], 200);
    }
}