@extends('layouts.master')
@section('title', 'Catalog — Buyee') 

@section('content')
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    {{-- Breadcrumbs --}}
    <nav class="pt-6 text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Catalog</li>
      </ol>
    </nav>
    
    {{-- Heading that adapts by ?group= --}}
    @php
        // Menggunakan request('category_id') untuk filter
        $currentCategoryId = request('category_id');
        $group = strtolower(request('group', 'all'));
        
        // Logika Heading (Default ke All Products)
        $headingText = 'All Products';
        $productCount = $products->count(); // Menggunakan count produk yang benar

        if ($currentCategoryId) {
            $currentCategory = $categories->firstWhere('id', $currentCategoryId);
            if ($currentCategory) {
                $headingText = $currentCategory->name;
            }
        } else if ($group !== 'all') {
             // Logic untuk group fashion statis (sesuai kode Anda)
             $titleMap = [
                'women'       => 'Women Fashion',
                'men'         => 'Men Fashion',
                'kids'        => 'Kids Fashion',
                'accessories' => 'Accessories',
            ];
            $headingText = $titleMap[$group] ?? 'Catalog';
        }
    @endphp
    
    <div class="mt-4 flex items-center justify-between">
      <h1 class="text-3xl font-extrabold" id="catalogHeading">{{ $headingText }}</h1>
      <div class="text-xs text-gray-500">Showing 1–{{ $productCount }} of {{ $productCount }} Products <i class="fa-solid fa-angle-down ml-1"></i></div>
    </div>
    
    <div class="mt-6 grid grid-cols-12 gap-8">
      
      {{-- SIDEBAR FILTERS (Kiri) --}}
      <aside class="col-span-12 md:col-span-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 md:p-5 filter-card">
          <div class="mb-4 flex items-center justify-between">
            <div class="text-sm font-semibold">Filters</div>
            {{-- Tombol Reset mengarah ke katalog tanpa filter --}}
            <a href="{{ route('catalog') }}" id="filterReset" class="text-xs text-gray-500 hover:text-gray-700">Reset</a>
          </div>
          
          {{-- KOREKSI UI: Category (Filter Dinamis dari Database) --}}
          <div class="border-t border-gray-100 pt-4">
            <div class="mb-3 flex items-center justify-between">
              <span class="text-sm font-semibold">Category</span>
              <a href="{{ route('catalog') }}" class="text-xs text-gray-500 hover:text-gray-700">All</a>
            </div>
            
            <div class="grid gap-1 text-sm">
                {{-- Tautan untuk SEMUA PRODUK --}}
                <a href="{{ route('catalog') }}"
                    class="flex items-center justify-between rounded-lg px-3 py-2 hover:bg-gray-100 
                           {{ !$currentCategoryId ? 'bg-gray-100 font-semibold text-gray-900' : 'text-gray-600' }}">
                    <span>All Products</span>
                    <i class="fa-solid fa-layer-group text-[10px] text-gray-400"></i>
                </a>
                
                {{-- Loop Kategori Dinamis --}}
                @foreach ($categories as $category)
                    @php
                        $isActive = $currentCategoryId == $category->id;
                    @endphp
                    <a href="{{ route('catalog', ['category_id' => $category->id]) }}"
                        class="flex items-center justify-between rounded-lg px-3 py-2 
                               {{ $isActive ? 'bg-indigo-50 font-semibold text-indigo-700 hover:bg-indigo-100' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span>{{ $category->name }}</span>
                        {{-- Asumsi products_count ada di Model Category --}}
                        <span class="text-xs font-medium">{{ $category->products_count ?? '' }}</span> 
                        <i class="fa-solid fa-chevron-right text-[10px] {{ $isActive ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                    </a>
                @endforeach
            </div>
          </div>
          
          {{-- Price, Colors, Size (Statis/Dummy untuk sementara)
          <div class="mt-6 border-t border-gray-100 pt-4">...</div>
          <div class="mt-6 border-t border-gray-100 pt-4">...</div>
          <div class="mt-6 border-t border-gray-100 pt-4">...</div> --}}

          <button id="applyFilter"
              class="mt-6 w-full rounded-md bg-black py-2 text-sm font-semibold text-white hover:bg-gray-900">
            Apply Filter
          </button>
        </div>
      </aside>
      
      {{-- GRID PRODUK UTAMA (Kanan) --}}
      <section class="col-span-12 md:col-span-9">
        
        {{-- Result Info --}}
        <div class="result-info bg-rose-500 text-white rounded-lg p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fa-solid fa-search me-2"></i>
                    <strong>Hasil pencarian untuk: "{{ request('search', $headingText) }}"</strong>
                    <br><small>Ditemukan {{ $productCount }} produk dari total produk</small>
                </div>
                <div>
                    <span class="badge bg-light text-dark">Showing 1–{{ $productCount }} of {{ $productCount }} Products</span>
                </div>
            </div>
        </div>

        {{-- GRID PRODUK DINAMIS --}}
        <div id="productGrid" class="grid grid-cols-2 gap-5 sm:grid-cols-3">
          
          @forelse ($products as $product)
            {{-- Product Card Dinamis --}}
            <a href="{{ route('product.detail', $product->id) }}"
                class="group rounded-xl border border-gray-200 bg-white p-3 hover:shadow-card transition"
                data-category="{{ $product->category_id }}" 
                data-price="{{ $product->price }}">
                
                <div class="aspect-[4/5] overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200 grid place-items-center">
                    @php
                        // Path gambar yang sudah benar
                        $imageUrl = $product->image ? '/' . $product->image : asset('images/placeholder.jpg'); 
                    @endphp
                    <img src="{{ $imageUrl }}"
                        class="h-full w-full object-cover transition duration-300 group-hover:scale-105" 
                        alt="{{ $product->name }}"
                        onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';"
                    >
                </div>
                
                <div class="mt-3 space-y-1">
                    <div class="text-[13px] font-semibold text-gray-800 group-hover:text-gray-900 line-clamp-1">
                        {{ $product->name }}
                    </div>
                    <div class="text-[10px] text-gray-500">{{ $product->category->name ?? 'N/A' }}</div>
                    
                    <div class="flex items-center gap-2">
                        <div class="text-[13px] font-extrabold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </a>
          @empty
            <div class="col-span-full text-center py-8 text-gray-500">
                <i class="fa-solid fa-magnifying-glass-minus fa-2x mb-3"></i>
                <p>Maaf, tidak ada produk ditemukan.</p>
            </div>
          @endforelse
        </div>
        
        {{-- Pagination --}}
        <div class="mt-8 flex items-center justify-center gap-2">
            {{-- Pagination logic will be added here --}}
        </div>
      </section>
    </div>
</main>
@endsection