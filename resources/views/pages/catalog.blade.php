@extends('layouts.master')
@section('title', 'Catalog — Buyee') 

@section('content')
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20 pt-24">
    {{-- Breadcrumbs (dipertahankan) --}}
    {{-- Logic PHP untuk Heading (dipertahankan) --}}
    @php
        $currentCategoryId = request('category_id');
        $searchQuery = request('search');

        // Logika Heading
        $headingText = 'All Products';
        if ($searchQuery) {
            $headingText = 'Hasil Pencarian untuk "' . $searchQuery . '"';
        } elseif ($currentCategoryId) {
            $currentCategory = $categories->firstWhere('id', $currentCategoryId);
            $headingText = $currentCategory ? $currentCategory->name : 'Filtered Products';
        }
        $productCount = $products->count();
    @endphp
    
    <div class="mt-4 flex items-center justify-between">
      <h1 class="text-3xl font-extrabold" id="catalogHeading">{{ $headingText }}</h1>
      <div class="text-xs text-gray-500">Showing 1–{{ $productCount }} of {{ $productCount }} Products <i class="fa-solid fa-angle-down ml-1"></i></div>
    </div>
    
    <div class="mt-6 grid grid-cols-12 gap-8">
      
      {{-- SIDEBAR FILTERS (Kiri) --}}
      <aside class="col-span-12 md:col-span-3">
        
        {{-- ✨ PERBAIKAN KRUSIAL: FORM UNTUK MENGIRIM DATA HARGA & SORTIR ✨ --}}
        <form method="GET" action="{{ route('catalog') }}">
            
            {{-- Pertahankan Query Pencarian yang Ada --}}
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            
            <div class="rounded-2xl border border-gray-200 bg-white p-4 md:p-5 filter-card">
                <div class="mb-4 flex items-center justify-between">
                    <div class="text-sm font-semibold">Filters</div>
                    {{-- Tombol Reset - Mengarahkan ke katalog bersih (tanpa filter/search) --}}
                    <a href="{{ route('catalog') }}" id="filterReset" class="text-xs text-gray-500 hover:text-gray-700">Reset</a>
                </div>
                
                {{-- Category Filter (HANYA MENAMPILKAN, filter dilakukan via link <a>) --}}
                <div class="border-t border-gray-100 pt-4">
                    <div class="mb-3 flex items-center justify-between">
                        <span class="text-sm font-semibold">Category</span>
                        <a href="{{ route('catalog', request()->except('category_id', 'page')) }}" class="text-xs text-gray-500 hover:text-gray-700">All</a>
                    </div>
                    
                    <div class="grid gap-1 text-sm">
                        {{-- Tautan SEMUA PRODUK --}}
                        <a href="{{ route('catalog', request()->except('category_id', 'page', 'sort', 'min_price', 'max_price')) }}"
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
                            {{-- Link Kategori harus mempertahankan parameter search dan mengabaikan parameter filter lainnya --}}
                            <a href="{{ route('catalog', array_merge(request()->only('search'), ['category_id' => $category->id])) }}"
                                class="flex items-center justify-between rounded-lg px-3 py-2 
                                      {{ $isActive ? 'bg-indigo-50 font-semibold text-indigo-700 hover:bg-indigo-100' : 'text-gray-600 hover:bg-gray-50' }}">
                                <span>{{ $category->name }}</span>
                                <i class="fa-solid fa-chevron-right text-[10px] {{ $isActive ? 'text-indigo-500' : 'text-gray-400' }}"></i>
                            </a>
                        @endforeach
                    </div>
                    
                    {{-- HIDDEN INPUT untuk category_id, agar filter harga bisa dikombinasikan dengan kategori yang sedang aktif --}}
                    @if($currentCategoryId)
                         <input type="hidden" name="category_id" value="{{ $currentCategoryId }}">
                    @endif
                </div>
                
                {{-- Filter Urutkan (Best Seller) --}}
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <div class="mb-3"><span class="text-sm font-semibold">Urutkan</span></div>
                    <div class="flex items-center space-x-3">
                        {{-- Checkbox ini akan mengirim 'sort=bestseller' jika dicentang --}}
                        <input type="checkbox" id="bestseller" name="sort" value="bestseller" 
                                class="h-4 w-4 rounded border-gray-300 text-black focus:ring-black"
                                {{ request('sort') == 'bestseller' ? 'checked' : '' }}>
                        <label for="bestseller" class="text-sm text-gray-700">Best Seller</label>
                    </div>
                </div>

                {{-- Filter Harga --}}
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <div class="mb-3"><span class="text-sm font-semibold">Harga</span></div>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-black focus:ring-black">
                        <span class="text-gray-400">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                class="w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-black focus:ring-black">
                    </div>
                </div>
                
                <input type="hidden" name="group" value="{{ request('group') }}">
                
                {{-- Tombol Terapkan Filter akan men-submit form GET --}}
                <button type="submit" id="TerapkanFilter"
                    class="mt-6 w-full rounded-md bg-black py-2 text-sm font-semibold text-white hover:bg-gray-900">
                    Terapkan Filter
                </button>
            </div>
        </form>
      </aside>
      
      {{-- GRID PRODUK UTAMA (Kanan) --}}
      <section class="col-span-12 md:col-span-9">
        
        {{-- Result Info: Tampilkan status pencarian jika ada query (dipertahankan) --}}
        @if(request('search'))
            <div class="result-info bg-rose-500 text-white rounded-lg p-4 mb-4">
                <strong>Hasil pencarian untuk: "{{ request('search') }}"</strong>
                <br><small>Ditemukan {{ $products->count() }} produk.</small>
            </div>
        @endif

        {{-- GRID PRODUK DINAMIS dengan FORELSE (dipertahankan) --}}
        <div id="productGrid" class="grid grid-cols-2 gap-5 sm:grid-cols-3">
          
          @forelse ($products as $product)
            {{-- Product Card Dinamis --}}
            <a href="{{ route('product.detail', $product->id) }}"
                class="group rounded-xl border border-gray-200 bg-white p-3 hover:shadow-card transition"
                data-category="{{ $product->category_id }}" 
                data-price="{{ $product->price }}">
                
                <div class="aspect-[4/5] overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200 grid place-items-center">
                    @php
                        // Path gambar: ambil dari image di DB
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
            {{-- Tampilan jika produk tidak ditemukan --}}
            <div class="col-span-full text-center py-8 text-gray-500">
                <i class="fa-solid fa-magnifying-glass-minus fa-2x mb-3"></i>
                <p>Maaf, tidak ada produk ditemukan untuk pencarian ini.</p>
                @if(request('search'))
                    <p class="text-sm text-gray-400">Coba kata kunci lain atau hapus filter.</p>
                @endif
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