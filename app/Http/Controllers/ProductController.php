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
        
        if ($request->filled('category') && ! $request->has('group')) {
            $request->merge(['group' => $request->query('category')]);
        }

        if ($request->expectsJson() || $request->is('api/*')) {
             return $this->apiIndex($request);
        }
        
        $pageTitle = 'Semua Produk'; 
        $filterSlug = $request->query('category') ?? $request->query('group');

        // --- FILTER KATEGORI BERDASARKAN SLUG ---
        if ($filterSlug && $filterSlug !== 'all') {
            // Cari kategori berdasarkan slug yang dikirim
            $activeCategory = $categories->firstWhere('slug', $filterSlug);
            
            // Fallback jika slug tidak ditemukan (menggunakan logika yang sudah ada di kode Anda sebelumnya)
            if (!$activeCategory) {
                 $activeCategory = $categories->first(function ($cat) use ($filterSlug) {
                    $slugs = [Str::slug($cat->name), $cat->slug];
                    return in_array($filterSlug, $slugs);
                 });
            }

            if ($activeCategory) {
                $pageTitle = $activeCategory->name;
                $query->where('category_id', $activeCategory->id);
            } 
        }
        
        // --- FILTER HARGA ---
        if ($request->filled('min_price')) { 
             $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) { 
             $query->where('price', '<=', $request->max_price);
        }
        
        
        // Ambil parameter 'sort' (dari checkbox Best Seller) dan 'sort_by' (dari dropdown sorting, jika ada)
        $sortByCheckbox = $request->input('sort');
        $sortByDropdown = $request->input('sort_by', 'latest');
        
        if ($sortByCheckbox === 'bestseller') {
            // Prioritas: Best Seller (dari checkbox)
            $query->orderBy('sales_count', 'desc');
        } else {
             // Sorting Harga atau Terbaru
             switch ($sortByDropdown) {
                case 'price_asc': $query->orderBy('price', 'asc'); break;
                case 'price_desc': $query->orderBy('price', 'desc'); break;
                // 'bestseller' di sini diabaikan karena sudah dihandle checkbox
                default: $query->orderBy('created_at', 'desc'); break; 
            }
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
     * API: Mendapatkan daftar produk dengan filter dan sorting.
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
     * API: Dapatkan produk unggulan
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
     * API: Dapatkan produk berdasarkan kategori
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