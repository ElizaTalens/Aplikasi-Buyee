<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        $query = Product::with('category')->where('is_active', true);
        
        // Check if this is an API request
        if ($request->expectsJson() || $request->is('api/*')) {
            // Apply filters for API
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->category_id);
            }
            
            if ($request->filled('search')) {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
            
            // Apply sorting
            $sortBy = $request->input('sort_by', 'latest');
            switch ($sortBy) {
                case 'price_asc': $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                case 'bestseller': $query->orderBy('sales_count', 'desc'); break;
                default: $query->orderBy('created_at', 'desc'); break;
            }

            $products = $query->paginate(12);
            return response()->json($products);
        }
        
        // Web request logic
        // --- LOGIKA UNTUK JUDUL HALAMAN & FILTER KATEGORI ---
        $pageTitle = 'All Products'; // Judul default
        
        if ($request->filled('group') && $request->group !== 'all') {
            $groupParam = Str::slug($request->group);

            $activeCategory = $categories->first(function ($category) use ($groupParam) {
                $normalizedOptions = collect([
                    $category->slug,
                    str_replace(['-fashion', '-category'], '', $category->slug),
                    str_replace(['fashion-', 'category-'], '', $category->slug),
                ])->map(fn ($value) => Str::slug($value))->unique();

                return $normalizedOptions->contains($groupParam);
            });

            if ($activeCategory) {
                $pageTitle = $activeCategory->name;
                $query->where('category_id', $activeCategory->id);
            } else {
                $possibleSlugs = [
                    $groupParam,
                    $groupParam . '-fashion',
                    $groupParam . '-category',
                    'fashion-' . $groupParam,
                    'category-' . $groupParam,
                ];

                $query->whereHas('category', function ($q) use ($possibleSlugs) {
                    $q->whereIn('slug', $possibleSlugs);
                });
            }
        }
        
        // Filter Harga
        if ($request->filled('min_price')) { /* ... */ }
        if ($request->filled('max_price')) { /* ... */ }
        
        // Fitur Pencarian & History
        if ($request->filled('search')) {
            // ... logika pencarian & history ...
        }

        // Logika Sorting
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'bestseller': $query->orderBy('sales_count', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        $products = $query->paginate(9)->withQueryString();

        // Kirim semua data yang dibutuhkan, termasuk $pageTitle
        return view('pages.catalog', compact('products', 'categories', 'pageTitle'));
    }

    /**
     * Pencarian produk dari navbar.
     */
    public function search(Request $request): View|RedirectResponse
    {
        $selectedHistory = $request->input('selected_history');
        $typedQuery = $request->input('q') ?? $request->input('search');
        $rawQuery = trim((string) ($selectedHistory ?? $typedQuery ?? ''));

        if ($request->has('clear_history')) {
            Session::forget('search_history');

            if ($rawQuery === '' && !$selectedHistory) {
                return back();
            }
        } elseif ($request->filled('remove_history')) {
            $remove = Str::lower($request->input('remove_history'));
            $history = collect(Session::get('search_history', []))
                ->reject(fn ($term) => Str::lower($term) === $remove)
                ->values()
                ->toArray();
            Session::put('search_history', $history);

            if ($rawQuery === '' && !$selectedHistory) {
                return back();
            }
        }

        if ($rawQuery === '') {
            return redirect()->route('catalog');
        }

        $queryTerm = Str::limit($rawQuery, 100, '');

        $history = collect(Session::get('search_history', []))
            ->reject(fn ($term) => Str::lower($term) === Str::lower($queryTerm))
            ->prepend($queryTerm)
            ->unique(fn ($value) => Str::lower($value))
            ->take(6)
            ->values()
            ->toArray();
        Session::put('search_history', $history);

        $wildcard = '%' . str_replace(' ', '%', $queryTerm) . '%';

        $products = Product::with('category')
            ->where('is_active', true)
            ->where(function ($builder) use ($wildcard) {
                $builder->where(function ($q) use ($wildcard) {
                    $q->where('name', 'like', $wildcard)
                      ->orWhere('description', 'like', $wildcard)
                      ->orWhere('short_description', 'like', $wildcard)
                      ->orWhere('sku', 'like', $wildcard);
                })->orWhereHas('category', function ($categoryQuery) use ($wildcard) {
                    $categoryQuery->where('name', 'like', $wildcard)
                                  ->orWhere('slug', 'like', $wildcard);
                });
            })
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('pages.search-result', [
            'products' => $products,
            'query' => $queryTerm,
        ]);
    }

    /**
     * Menampilkan halaman detail untuk satu produk.
     */
    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }
        $product->load('category');
        
        // Check if this is an API request
        if (request()->expectsJson() || request()->is('api/*')) {
            return response()->json($product);
        }
        
        return view('pages.product-details', compact('product'));
    }

    /**
     * API: Get all products with pagination and filters
     */
    public function apiIndex(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);
        
        // Apply filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }
        
        // Apply sorting
        $sortBy = $request->input('sort_by', 'latest');
        switch ($sortBy) {
            case 'price_asc': $query->orderBy('price', 'asc'); break;
            case 'price_desc': $query->orderBy('price', 'desc'); break;
            case 'bestseller': $query->orderBy('sales_count', 'desc'); break;
            default: $query->orderBy('created_at', 'desc'); break;
        }

        $products = $query->paginate(12);
        return response()->json($products);
    }

    /**
     * API: Get featured products
     */
    public function featured()
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();
            
        return response()->json($products);
    }

    /**
     * API: Get products by category
     */
    public function byCategory($categoryId)
    {
        $products = Product::with('category')
            ->where('is_active', true)
            ->where('category_id', $categoryId)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return response()->json($products);
    }
}
