@extends('layouts.master')

@section('title', 'Selamat Datang di Buyee - Belanja Online Mudah & Menyenangkan')

@section('content')

<!-- quick CSS untuk menghilangkan garis/garis horisontal yang muncul -->
<style>
  /* sembunyikan hr global yang tidak diinginkan */
  hr { display: none !important; }

  /* pastikan track kategori tidak memunculkan garis bawah */
  #cat-track { border-bottom: none !important; box-shadow: none !important; }

  /* jika ada elemen separator tak terduga, sembunyikan juga */
  .page-separator, .separator-line { display: none !important; }

  /* backup: jika ada border pada container berikut, nonaktifkan */
  .max-w-7xl > .border-b, .max-w-7xl + .my-24 { border-top: none !important; }

  /* Hapus garis horizontal sisa (defensive) */
  #cat-track,
  #cat-track a,
  #cat-track .group,
  .max-w-7xl,
  .max-w-7xl > * {
    border-top: none !important;
    border-bottom: none !important;
    box-shadow: none !important;
  }

  /* sembunyikan elemen separator umum yang kadang dibuat secara dinamis */
  hr,
  .page-separator,
  .separator-line,
  .line-divider {
    display: none !important;
  }

  /* sembunyikan pseudo-element yang membuat garis */
  #cat-track::before,
  #cat-track::after,
  .max-w-7xl::before,
  .max-w-7xl::after {
    display: none !important;
  }

  /* fallback: sembunyikan elemen tipis 1px yang mungkin muncul */
  div[style*="height:1px"], div[style*="height: 1px"], div[style*="border-top: 1px"], div[style*="border-bottom: 1px"] {
    display: none !important;
  }

  /* Tab underline control (gunakan kelas .active) */
  .tab-button { position: relative; cursor: pointer; font-weight: 600; transition: color .18s ease, font-weight .12s ease; }
  /* saat hover atau aktif, buat teks tebal (sama) */
  .tab-button:hover,
  .tab-button.active { font-weight: 700; color: #111827; }
  .tab-button::after{
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: -1px;
    height: 3px;
    background: #111827; /* warna underline (sesuaikan) */
    border-radius: 4px;
    transform-origin: left center;
    transform: scaleX(0);
    opacity: 0;
    transition: transform .18s ease, opacity .18s ease;
  }
  .tab-button.active::after{
    transform: scaleX(1);
    opacity: 1;
  }

  /* pastikan hover/active visual tetap bekerja */
  .tab-button:hover { color: #111827; }
</style>

    {{-- ======================================================= --}}
    {{-- |                BAGIAN 1: HERO BANNER                | --}}
    {{-- ======================================================= --}}
    <section class="mx-auto max-w-7xl">
        @php
            $bannerUrl = asset('images/banner.jpg');
        @endphp
        <div class="relative overflow-hidden rounded-2xl">
            <img 
                src="{{ $bannerUrl }}" 
                alt="Buyee Banner" 
                class="w-full h-auto object-cover"
                onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'"
            >
        </div>
    </section>
    
    {{-- ======================================================= --}}
    {{-- |           BAGIAN 2: BROWSE BY CATEGORY            | --}}
    {{-- ======================================================= --}}
    <section class="mt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold">Kategori</h3>
            <div class="flex items-center gap-2">
                <button id="cat-prev" class="grid h-9 w-9 place-items-center rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-40" aria-label="Scroll left">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button id="cat-next" class="grid h-9 w-9 place-items-center rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50" aria-label="Scroll right">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
      
        <div id="cat-track" class="mt-5 flex gap-4 overflow-x-auto snap-x scroll-smooth" style="scrollbar-width: none; -ms-overflow-style: none;">        
            {{-- All Products --}}
            <a href="{{ route('catalog') }}" class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
                <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                    <img src="{{ asset('images/carts.jpg') }}" alt="All Products" class="h-full w-full object-cover" loading="lazy" />
                </div>
                <div class="text-base font-semibold text-gray-700">Semua Produk</div>
            </a>

            {{-- Dynamic categories --}}
            @foreach ($categories ?? [] as $category)
                @php
                    $categorySlug = $category->slug ?? \Illuminate\Support\Str::slug($category->name);
                    $categoryImagePath = $category->image_url;
                    if ($categoryImagePath && (filter_var($categoryImagePath, FILTER_VALIDATE_URL) || \Illuminate\Support\Str::startsWith($categoryImagePath, '//'))) {
                        $categoryImageUrl = $categoryImagePath;
                    } elseif ($categoryImagePath) {
                        $categoryImageUrl = asset('storage/' . $categoryImagePath);
                    } else {
                        $categoryImageUrl = asset('images/placeholder.jpg');
                    }
                @endphp

                <a href="{{ route('catalog', array_merge(request()->except(['page','group']), ['group' => $categorySlug])) }}" class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
                    <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                        <img src="{{ $categoryImageUrl }}" alt="{{ $category->name }}" class="h-full w-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}'">
                    </div>
                    <div class="text-base font-semibold text-gray-700">{{ $category->name }}</div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ======================================================= --}}
    {{-- |          BAGIAN 3: PRODUCT GRID TABS              | --}}
    {{-- ======================================================= --}}
    <section class="mt-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Tabs --}}
        <div class="flex items-center gap-6 border-b border-gray-200">
            <button type="button" class="tab-button relative text-lg pb-3 font-semibold text-gray-900 after:absolute after:inset-x-0 after:-bottom-[1px] after:h-0.5 after:bg-gray-900" data-tab="new-arrival">
                Produk Terbaru
            </button>
            <button type="button" class="tab-button pb-3 text-lg text-gray-500 hover:text-gray-900" data-tab="bestseller">
                Produk Terlaris
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
                <div class="col-span-full text-center py-8 text-gray-500"><p>Belum ada produk Terlaris.</p></div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            <a href="{{ route('catalog') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-5 text-sm font-semibold hover:bg-gray-50">
                Lihat Semua
            </a>
        </div>
    </section>

    {{-- Tambahkan script untuk tabs, carousel kategori, dan wishlist --}}
    <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Tabs
      const tabs = document.querySelectorAll('.tab-button');
      const contents = document.querySelectorAll('.tab-content');

      function showTab(name) {
        contents.forEach(c => c.style.display = 'none');
        const active = document.getElementById('tab-content-' + name);
        if (active) active.style.display = (name === 'new-arrival' ? 'grid' : 'grid');

        tabs.forEach(b => {
          if (b.dataset.tab === name) {
            b.classList.remove('text-gray-500');
            b.classList.add('text-gray-900','after:absolute','after:inset-x-0','after:-bottom-[1px]','after:h-0.5','after:bg-gray-900');
          } else {
            b.classList.remove('text-gray-900','after:absolute','after:inset-x-0','after:-bottom-[1px]','after:h-0.5','after:bg-gray-900');
            b.classList.add('text-gray-500');
          }
        });
      }

      tabs.forEach(b => b.addEventListener('click', () => showTab(b.dataset.tab)));
      // default
      showTab('new-arrival');

      // Category track scroll buttons
      const track = document.getElementById('cat-track');
      const btnPrev = document.getElementById('cat-prev');
      const btnNext = document.getElementById('cat-next');
      if (track && btnPrev && btnNext) {
        btnPrev.addEventListener('click', () => {
          track.scrollBy({ left: -Math.floor(track.clientWidth * 0.7), behavior: 'smooth' });
        });
        btnNext.addEventListener('click', () => {
          track.scrollBy({ left: Math.floor(track.clientWidth * 0.7), behavior: 'smooth' });
        });
      }

      // Wishlist toggle (AJAX)
      window.toggleWishlist = async function(productId, el) {
        try {
          const resp = await fetch("{{ route('wishlist.toggle') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'Accept': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ product_id: productId })
          });

          if (resp.status === 401) {
            // redirect ke login dengan return url
            localStorage.setItem('pending_wishlist_product', productId);
            window.location.href = "{{ route('login') }}?redirect=" + encodeURIComponent(window.location.href);
            return;
          }

          const data = await resp.json();
          if (resp.ok) {
            // toggle icon visual
            if (el) {
              const icon = el.querySelector('i');
              if (icon) {
                icon.classList.toggle('fa-solid');
                icon.classList.toggle('fa-regular');
              }
            }
            // optional: tampilkan toast sederhana
            const t = document.createElement('div');
            t.textContent = data.message || 'Berhasil';
            t.style.position = 'fixed';
            t.style.left = '50%';
            t.style.transform = 'translateX(-50%)';
            t.style.top = '16px';
            t.style.padding = '8px 12px';
            t.style.background = resp.ok ? 'rgba(0,0,0,0.85)' : '#ef4444';
            t.style.color = '#fff';
            t.style.borderRadius = '8px';
            t.style.zIndex = 9999;
            document.body.appendChild(t);
            setTimeout(() => t.remove(), 1800);
          } else {
            console.error(data);
            alert(data.message || 'Gagal menambahkan wishlist.');
          }
        } catch (err) {
          console.error(err);
          alert('Terjadi kesalahan. Coba lagi.');
        }
      };

      // Jika ada pending wishlist setelah login, kirim otomatis
      const pending = localStorage.getItem('pending_wishlist_product');
      if (pending && "{{ auth()->check() }}" === "1") {
        // cari tombol wishlist untuk produk itu dan panggil toggleWishlist
        const btn = document.querySelector(`.wishlist-btn[data-product-id="${pending}"]`);
        if (btn) toggleWishlist(pending, btn);
        localStorage.removeItem('pending_wishlist_product');
      }
    });
    </script>
@endsection