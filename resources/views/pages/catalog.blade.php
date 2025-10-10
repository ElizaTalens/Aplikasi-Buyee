<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Catalog — Buyee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased">

  {{-- NAVBAR --}}
  @include('layouts.navbar')

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">

    {{-- Breadcrumbs --}}
    <nav class="pt-30 text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Catalog</li>
      </ol>
    </nav>

    {{-- Heading that adapts by ?group= --}}
    @php
      $group = strtolower(request('group', 'all'));
      $titleMap = [
        'all'         => 'All Products',
        'women'       => 'Women Fashion',
        'men'         => 'Men Fashion',
        'kids'        => 'Kids Fashion',
        'accessories' => 'Accessories',
      ];
      $headingText = $titleMap[$group] ?? 'Catalog';
    @endphp

    <div class="mt-4 flex items-center justify-between">
      <h1 class="text-3xl font-extrabold" id="catalogHeading">{{ $headingText }}</h1>
      <div class="text-xs text-gray-500">Showing 1–10 of 100 Products <i class="fa-solid fa-angle-down ml-1"></i></div>
    </div>

    <div class="mt-6 grid grid-cols-12 gap-8">

      {{-- SIDEBAR FILTERS --}}
      <aside class="col-span-12 md:col-span-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-4 md:p-5">
          <div class="mb-4 flex items-center justify-between">
            <div class="text-sm font-semibold">Filters</div>
            <button id="filterReset" class="text-xs text-gray-500 hover:text-gray-700">Reset</button>
          </div>

          {{-- Category (adapts by ?group=) --}}
          <div class="border-t border-gray-100 pt-4">
            <div class="mb-3 flex items-center justify-between">
              <span class="text-sm font-semibold">Category</span>
              <a href="{{ route('catalog', ['group' => 'all']) }}" class="text-xs text-gray-500 hover:text-gray-700">All</a>
            </div>

            @if ($group === 'all')
              {{-- TOP LEVEL groups --}}
              <div class="grid gap-2 text-sm">
                <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50"
                   href="{{ route('catalog', ['group' => 'women']) }}">
                  <span>Women Fashion</span> <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50"
                   href="{{ route('catalog', ['group' => 'men']) }}">
                  <span>Men Fashion</span> <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50"
                   href="{{ route('catalog', ['group' => 'kids']) }}">
                  <span>Kids Fashion</span> <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
                <a class="flex items-center justify-between rounded-lg border border-gray-200 px-3 py-2 hover:bg-gray-50"
                   href="{{ route('catalog', ['group' => 'accessories']) }}">
                  <span>Accessories</span> <i class="fa-solid fa-chevron-right text-[10px] text-gray-400"></i>
                </a>
              </div>
            @else
              {{-- SUBCATEGORIES for a group --}}
              @php
                $subcatsByGroup = [
                  'women'       => ['T-shirts','Shirts','Hoodie','Dress','Skirts'],
                  'men'         => ['T-shirts','Shirts','Hoodie','Pants','Outerwear'],
                  'kids'        => ['T-shirts','Shirts','Hoodie','Shorts','Sets'],
                  'accessories' => ['Bags','Belts','Hats','Jewelry'],
                ];
                $subcats = $subcatsByGroup[$group] ?? ['T-shirts','Shirts','Hoodie'];
              @endphp
              <div class="space-y-2 text-sm text-gray-700">
                @foreach ($subcats as $c)
                  <label class="flex items-center gap-2">
                    <input type="checkbox" class="rounded" value="{{ Str::slug($c) }}"> {{ $c }}
                  </label>
                @endforeach
              </div>
            @endif
          </div>

          {{-- Price --}}
          <div class="mt-6 border-t border-gray-100 pt-4">
            <div class="text-sm font-semibold mb-3">Price</div>
            <input id="priceMin" type="range" min="50" max="200" value="60" class="w-full">
            <input id="priceMax" type="range" min="50" max="200" value="160" class="mt-2 w-full">
            <div class="mt-2 flex justify-between text-xs text-gray-500">
              <span>$<span id="priceMinVal">60</span></span>
              <span>$<span id="priceMaxVal">160</span></span>
            </div>
          </div>

          {{-- Colors --}}
          <div class="mt-6 border-t border-gray-100 pt-4">
            <div class="text-sm font-semibold mb-3">Colors</div>
            <div id="colorFilter" class="flex flex-wrap gap-3">
              @php $colors=['#0ea5e9','#22c55e','#f59e0b','#ef4444','#6366f1','#111827','#eab308','#14b8a6']; @endphp
              @foreach($colors as $hex)
                <button type="button" class="h-6 w-6 rounded-full ring-1 ring-gray-300"
                        style="background: {{ $hex }}" data-color="{{ $hex }}" aria-pressed="false"></button>
              @endforeach
            </div>
          </div>

          {{-- Size --}}
          <div class="mt-6 border-t border-gray-100 pt-4">
            <div class="text-sm font-semibold mb-3">Size</div>
            <div id="sizeFilter" class="flex flex-wrap gap-2">
              @foreach (['Small'=>'S','Medium'=>'M','Large'=>'L','X-Large'=>'XL'] as $label=>$val)
                <button type="button"
                        class="size-pill inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs text-gray-700"
                        data-size="{{ $val }}" aria-pressed="false">{{ $label }}</button>
              @endforeach
            </div>
          </div>

          <button id="applyFilter"
                  class="mt-6 w-full rounded-md bg-black py-2 text-sm font-semibold text-white hover:bg-gray-900">
            Apply Filter
          </button>
        </div>
      </aside>

      {{-- GRID PRODUK --}}
      <section class="col-span-12 md:col-span-9">
        <div id="productGrid" class="grid grid-cols-2 gap-5 sm:grid-cols-3">
          @for ($i=1; $i<=9; $i++)
            <a href="{{ route('product.detail') }}"
               class="group rounded-xl border border-gray-200 bg-white p-3 hover:shadow-card transition">
              <div class="aspect-[4/5] overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
                <img src="{{ asset('images/p1.jpg') }}"
                     class="h-full w-full object-cover transition duration-300 group-hover:scale-105" alt="Product">
              </div>
              <div class="mt-3 space-y-1">
                <div class="text-[13px] font-semibold text-gray-800 group-hover:text-gray-900">
                  Sample Product {{ $i }}
                </div>
                <div class="flex items-center gap-1 text-[10px] text-amber-500">
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                  <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                  <i class="fa-regular fa-star"></i>
                  <span class="ml-1 text-gray-400">4.0/5</span>
                </div>
                <div class="flex items-center gap-2">
                  <div class="text-[13px] font-extrabold">$145</div>
                  <div class="text-[11px] text-gray-400 line-through">$180</div>
                  <div class="rounded bg-rose-50 px-1.5 py-0.5 text-[10px] font-semibold text-rose-500">-20%</div>
                </div>
              </div>
            </a>
          @endfor
        </div>

        {{-- Pagination --}}
        <div class="mt-8 flex items-center justify-center gap-2">
          <button class="rounded-md border border-gray-200 px-3 py-2 text-xs hover:bg-gray-50">
            <i class="fa-solid fa-angle-left mr-2"></i>Previous
          </button>
          @for ($p=1; $p<=10; $p++)
            <button class="page-btn rounded-md border border-gray-200 px-3 py-2 text-xs {{ $p===1 ? 'bg-black text-white' : 'hover:bg-gray-50' }}">{{ $p }}</button>
          @endfor
          <button class="rounded-md border border-gray-200 px-3 py-2 text-xs hover:bg-gray-50">
            Next<i class="fa-solid fa-angle-right ml-2"></i>
          </button>
        </div>
      </section>
    </div>
  </main>

  {{-- FOOTER --}}
  @include('layouts.footer')

</body>
<<<<<<< HEAD
</html>
=======
</html>
>>>>>>> 3efa58752c39911d4116c99d961d7d5bbb85d307
