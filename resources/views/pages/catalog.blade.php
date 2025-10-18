<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-t" />
  <title>{{ $pageTitle }} — Buyee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased">

  @include('layouts.navbar')

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    <nav class="text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Katalog</li>
      </ol>
    </nav>
    
    <div class="mt-4 flex items-center justify-between">
      <h1 class="text-3xl font-extrabold">{{ $pageTitle }}</h1>
      @if($products->total() > 0)
        <div class="text-xs text-gray-500">
          Menampilkan {{ $products->firstItem() }}–{{ $products->lastItem() }} dari {{ $products->total() }} Produk
        </div>
      @endif
    </div>

    <div class="mt-6 grid grid-cols-12 gap-8">
      <aside class="col-span-12 md:col-span-3">
        <form id="filterForm" action="{{ route('catalog') }}" method="GET">
          <div class="rounded-2xl border border-gray-200 bg-white p-4 md:p-5">
            <div class="mb-4 flex items-center justify-between">
              <div class="text-sm font-semibold">Filter</div>
              <a href="{{ route('catalog') }}" class="text-xs text-gray-500 hover:text-gray-700">Reset</a>
            </div>

            {{-- Filter Kategori --}}
            <div class="border-t border-gray-100 pt-4">
              <div class="mb-3"><span class="text-sm font-semibold">Kategori</span></div>
              <div class="grid gap-2 text-sm">
                @php
                  // Aktif ketika tidak ada group di query string (menampilkan semua produk)
                  $isAllActive = !request()->has('group') || request('group') === '' || request('group') === 'all';
                @endphp

                <!-- All Produk: hapus filter kategori tapi pertahankan query lain (mis. sort/min_price/max_price) -->
                <a class="flex items-center justify-between rounded-lg px-3 py-2 transition-colors
                   {{ $isAllActive ? 'bg-gray-100 font-bold text-gray-900' : 'border border-gray-200 hover:bg-gray-50' }}"
                   href="{{ route('catalog', request()->except(['page','group'])) }}">
                  <span>Semua Produk</span> <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>

                @foreach ($categories as $category)
                  @php
                    $slug = str_replace('-fashion', '', $category->slug);
                    $isActive = request('group') == $slug;
                  @endphp
                  <a class="flex items-center justify-between rounded-lg px-3 py-2 transition-colors
                     {{ $isActive ? 'bg-gray-100 font-bold text-gray-900' : 'border border-gray-200 hover:bg-gray-50' }}"
                     href="{{ route('catalog', array_merge(request()->except(['page','group']), ['group' => $slug])) }}">
                    <span>{{ $category->name }}</span> <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                  </a>
                @endforeach
              </div>
            </div>
            
            {{-- Filter Urutkan (Best Seller) --}}
            <div class="border-t border-gray-100 pt-4 mt-4">
              <div class="mb-3"><span class="text-sm font-semibold">Urutkan</span></div>
              <div class="flex items-center space-x-2">
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
            
            {{-- Hidden input & Tombol Terapkan --}}
            <input type="hidden" name="group" value="{{ request('group') ?? request('category') }}">
            <button type="submit" class="mt-6 w-full rounded-md bg-black py-2 text-sm font-semibold text-white hover:bg-gray-900">
              Terapkan Filter
            </button>
          </div>
        </form>
      </aside>

      <section class="col-span-12 md:col-span-9">
        <div id="productGrid" class="grid grid-cols-2 gap-5 sm:grid-cols-3">
          @forelse ($products as $product)
            <div class="group rounded-xl border border-gray-200 bg-white p-3 hover:shadow-card transition relative">
              <a href="{{ route('product.detail', $product->slug) }}" class="block">
                <div class="aspect-[4/5] overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                  <img src="{{ isset($product->images[0]) ? asset('storage/' . $product->images[0]) : asset('images/placeholder.jpg') }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105" alt="{{ $product->name }}">
                </div>
                <div class="mt-3 space-y-1">
                  <div class="text-[13px] font-semibold text-gray-800 group-hover:text-gray-900">{{ $product->name }}</div>
                  <div class="flex items-center gap-2">
                    <div class="text-[13px] font-extrabold">Rp{{ number_format($product->price) }}</div>
                    @if($product->compare_price)
                      <div class="text-[11px] text-gray-400 line-through">Rp{{ number_format($product->compare_price) }}</div>
                    @endif
                  </div>
                </div>
              </a>
            </div>
          @empty
            <div class="col-span-full text-center py-16">
              <h2 class="text-2xl font-semibold text-gray-700">Oops! Produk tidak ditemukan.</h2>
              <p class="text-gray-500 mt-2">Coba hapus beberapa filter untuk melihat lebih banyak produk.</p>
            </div>
          @endforelse
        </div>
        <div class="mt-8">
            {{ $products->links() }}
        </div>
      </section>
    </div>
  </main>

  @include('layouts.footer')
</body>
</html>