@extends('layouts.master')

@section('title', 'Selamat Datang di Buyee - Belanja Online Mudah & Menyenangkan')

@section('content')

    {{-- ======================================================= --}}
    {{-- |                BAGIAN 1: HERO BANNER                | --}}
    {{-- ======================================================= --}}
    <section class="mx-auto max-w-7xl">
        <div class="relative overflow-hidden rounded-2xl bg-[#2a242b] p-10 text-white min-h-[360px] flex items-center">
            {{-- Text --}}
            <div class="max-w-xl">
                @auth
                    {{-- Sapaan untuk PENGGUNA yang sudah login --}}
                    <p class="text-sm text-white/70">Selamat Datang Kembali, {{ Auth::user()->name }}!</p>
                    <h2 class="mt-2 text-5xl font-extrabold tracking-tight">
                        Buyee: Belanja Online
                    </h2>
                    <p class="mt-2 text-xs text-white/60">
                        Jelajahi ribuan produk UMKM pilihan kami. Saatnya dukung produk lokal!
                    </p>
                    <a href="{{ route('catalog') }}" class="mt-6 inline-flex h-9 items-center justify-center rounded-md border border-white/40 px-4 text-xs font-semibold hover:bg-white hover:text-[#2a242b] transition">
                        Mulai Belanja
                    </a>
                @else
                    {{-- Sapaan untuk TAMU (Guest) --}}
                    <p class="text-sm text-white/70">Belanja Online Pilihan Lokal</p>
                    <h2 class="mt-2 text-5xl font-extrabold tracking-tight">
                        Buyee <span class="text-white/90"></span>
                    </h2>
                    <p class="mt-2 text-xs text-white/60">
                        Temukan berbagai produk unggulan UMKM, mulai dari kerajinan tangan hingga kuliner unik.
                    </p>
                    <a href="{{ route('catalog') }}" class="mt-6 inline-flex h-9 items-center justify-center rounded-md border border-white/40 px-4 text-xs font-semibold hover:bg-white hover:text-[#2a242b] transition">
                        Lihat Katalog
                    </a>
                @endauth
            </div>

            {{-- Phone visual (kanan) --}}
            <div class="absolute inset-y-0 right-0 hidden md:block">
                <div class="h-full w-[420px] bg-[radial-gradient(80%_80%_at_70%_30%,rgba(255,255,255,.06),transparent_60%)]"></div>
            </div>
        </div>
    </section>

    {{-- ======================================================= --}}
    {{-- |           BAGIAN 2: BROWSE BY CATEGORY            | --}}
    {{-- ======================================================= --}}
    <section class="my-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- ... (Judul dan tombol scroll tidak diubah) ... --}}
      
        <div id="cat-track" class="mt-5 flex gap-4 overflow-x-auto snap-x scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">
            <style>#cat-track::-webkit-scrollbar { display: none; }</style>

            {{-- Kartu statis untuk "All Products" --}}
            <a href="{{ route('catalog') }}" class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
                <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                    <img src="{{ asset('images/carts.jpg') }}" alt="All Products" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="text-base font-semibold text-gray-700">All Products</div>
            </a>

            {{-- Loop dinamis untuk setiap kategori dari database --}}
            {{-- ASUMSI: $categories sudah di-passing dari controller --}}
            @foreach ($categories as $category)
                @php
                    $categoryImagePath = $category->image_url; 
                    
                    // Cek apakah path adalah URL eksternal atau path storage lokal
                    if (filter_var($categoryImagePath, FILTER_VALIDATE_URL) || Str::startsWith($categoryImagePath, '//')) {
                         $categoryImageUrl = $categoryImagePath;
                    } elseif (isset($categoryImagePath)) {
                         // Ini menangani path lokal: categories/makanan-minuman.jpg
                         $categoryImageUrl = asset('storage/' . $categoryImagePath); 
                    } else {
                         // Fallback jika NULL
                         $categoryImageUrl = asset('images/placeholder.jpg'); 
                    }

                    $categorySlug = $category->slug ?? \Illuminate\Support\Str::slug($category->name);
                @endphp
                
                <a href="{{ route('catalog', ['category' => $categorySlug]) }}" 
                   class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
                    
                    <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                        <img src="{{ $categoryImageUrl }}" 
                             alt="{{ $category->name }}" 
                             class="h-full w-full object-cover" 
                             loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';" 
                        />
                    </div>
                    <div class="text-base font-semibold text-gray-700">{{ $category->name }}</div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ======================================================= --}}
    {{-- |          BAGIAN 3: PRODUCT GRID TABS              | --}}
    {{-- ======================================================= --}}
    <section class="my-24 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Tabs --}}
        <div class="flex items-center gap-6 border-b border-gray-200">
            <button type="button" class="tab-button relative text-lg pb-3 font-semibold text-gray-900 after:absolute after:inset-x-0 after:-bottom-[1px] after:h-0.5 after:bg-gray-900" data-tab="new-arrival">
                New Arrival
            </button>
            <button type="button" class="tab-button pb-3 text-lg text-gray-500 hover:text-gray-900" data-tab="bestseller">
                Bestseller
            </button>
        </div>

        {{-- Konten Tab "New Arrival" --}}
        <div id="tab-content-new-arrival" class="tab-content mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @forelse ($newArrivals as $product)
                {{-- Card Produk --}}
                <div class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
                    <a href="{{ route('product.detail', $product->slug) }}" class="block">
                        <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
                            <img src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="mt-3 space-y-1">
                            <p class="line-clamp-2 text-lg font-bold text-gray-900">{{ $product->name }}</p>
                            <p class="text-[15px] font-bold">Rp{{ number_format($product->price) }}</p> 
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500"><p>Produk baru akan segera hadir!</p></div>
            @endforelse
        </div>

        {{-- Konten Tab "Bestseller" --}}
        <div id="tab-content-bestseller" class="tab-content mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4" style="display: none;">
            @forelse ($bestsellers as $product)
                {{-- Card Produk --}}
                <div class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
                    {{-- Wishlist Button --}}
                    @auth
                    <button type="button" 
                            class="wishlist-btn absolute top-4 right-4 z-10 grid h-8 w-8 place-items-center rounded-full bg-black/10 text-white backdrop-blur-sm transition hover:bg-black/20" 
                            data-product-id="{{ $product->id }}" 
                            title="Add to Wishlist"
                            onclick="toggleWishlist({{ $product->id }}, this)">
                        <i class="fa-regular fa-heart"></i>
                    </button>
                    @endauth
                    
                    <a href="{{ route('product.detail', $product->slug) }}" class="block">
                        <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
                            <img src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="mt-3 space-y-1">
                            <p class="line-clamp-2 text-lg font-bold text-gray-900">{{ $product->name }}</p>
                            <p class="text-[15px] font-bold">Rp{{ number_format($product->price) }}</p>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500"><p>Belum ada produk bestseller.</p></div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('catalog') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-5 text-sm font-semibold hover:bg-gray-50">
                Lihat Semua
            </a>
        </div>
    </section>

@endsection