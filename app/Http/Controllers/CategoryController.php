<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori.
     */
    public function index(Request $request): JsonResponse|View
    {
        $query = Category::query();

        if ($request->has('active_only') && $request->active_only) {
            $query->where('is_active', true);
        }

        $categories = $query->orderBy('name')->get();

        if ($request->expectsJson()) {
            return response()->json($categories);
        }

        return view('categories.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create(): View
    {
        return view('categories.create');
    }

    /**
     * Menyimpan kategori baru ke dalam database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    /**
     * Menampilkan kategori yang ditentukan.
     */
    public function show(string $id): JsonResponse|View
    {
        $category = Category::with(['products' => function($query) {
            $query->where('is_active', true);
        }])->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json($category);
        }

        return view('categories.show', compact('category'));
    }

    /**
     * Menampilkan form untuk mengedit kategori yang ditentukan.
     */
    public function edit(string $id): View
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Pembaruan kategori yang ditentukan.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug,' . $id,
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    /**
     * Hapus kategori yang ditentukan.
     */
    public function destroy(string $id): JsonResponse
    {
        $category = Category::findOrFail($id);
        
        if ($category->products()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing products'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    /**
     * Mengambil semua kategori yang aktif.
     */
    public function active(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }
}
