<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Your Wishlist — Buyee</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased">

  @include('layouts.navbar')

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    {{-- Breadcrumbs --}}
    <nav class="pt-30 text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Wishlist</li>
      </ol>
    </nav>

    <h1 class="mt-3 text-[44px] font-extrabold tracking-tight">Your Wishlist</h1>

    <div class="mt-6 grid grid-cols-12 gap-8" data-wishlist>
      {{-- LEFT: items --}}
      <section class="col-span-12 lg:col-span-8">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 overflow-hidden">

          {{-- Item 1: Gradient Graphic T-shirt (Contoh data statis) --}}
          <div class="flex items-center gap-6 p-6" data-item data-id="1">
            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
              <img src="{{ asset('images/p1.jpg') }}" alt="Gradient Graphic T-shirt" class="h-full w-full object-top">
            </div>

            <div class="min-w-0 flex-1">
              <h3 class="text-xl font-extrabold">Gradient Graphic T-shirt</h3>
              <div class="mt-1 text-sm text-gray-600 space-y-0.5">
                <div>Size: <span class="font-medium">Large</span></div>
                <div>Color: <span class="font-medium">White</span></div>
              </div>
              <div class="mt-3 text-2xl font-extrabold price">$145</div>
            </div>

            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
              <button class="text-rose-600 hover:text-rose-700 p-2" data-remove title="Remove">
                <i class="fa-solid fa-trash-can fa-lg"></i>
              </button>
              
              <button class="flex items-center rounded-full bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-700 text-sm transition" data-move-to-cart>
                Pindahkan ke Keranjang
              </button>
            </div>
          </div>

          <hr class="border-gray-200">

          {{-- Item 2: Checkered Shirt (Contoh data statis) --}}
          <div class="flex items-center gap-6 p-6" data-item data-id="2">
            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
              <img src="{{ asset('images/men.jpg') }}" alt="Checkered Shirt" class="h-full w-full object-top">
            </div>

            <div class="min-w-0 flex-1">
              <h3 class="text-xl font-extrabold">Checkered Shirt</h3>
              <div class="mt-1 text-sm text-gray-600 space-y-0.5">
                <div>Size: <span class="font-medium">Medium</span></div>
                <div>Color: <span class="font-medium">Red</span></div>
              </div>
              <div class="mt-3 text-2xl font-extrabold price">$180</div>
            </div>

            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
              <button class="text-rose-600 hover:text-rose-700 p-2" data-remove title="Remove">
                <i class="fa-solid fa-trash-can fa-lg"></i>
              </button>
              
              <button class="flex items-center rounded-full bg-indigo-600 px-4 py-2 text-white font-semibold hover:bg-indigo-700 text-sm transition" data-move-to-cart>
                Pindahkan ke Keranjang
              </button>
            </div>
          </div>
          
          <hr class="border-gray-200">

          {{-- Row Placeholder (jika wishlist kosong) --}}
          <div class="p-12 text-center text-gray-500 hidden" data-empty-state>
            <i class="fa-regular fa-heart fa-3x mb-4"></i>
            <p class="text-xl font-semibold">Wishlist kamu masih kosong.</p>
            <p class="text-sm mt-2">Tambahkan produk yang kamu sukai dari halaman katalog.</p>
          </div>

        </div>
      </section>

      {{-- RIGHT: Action Summary --}}
      <aside class="col-span-12 lg:col-span-4">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-6">
          <h3 class="text-2xl font-extrabold text-rose-600">Wishlist Actions</h3>

          <div class="mt-6 text-lg font-semibold text-gray-700">
            Total <span id="itemCount">2</span> item di wishlist
          </div>

          <button
            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-rose-600 px-6 py-4 text-white font-semibold hover:bg-rose-700 transition" data-move-all-to-cart>
            Pindahkan Semua ke Keranjang
            
          </button>
          
          <button
            class="mt-4 inline-flex w-full items-center justify-center rounded-full border border-gray-300 px-6 py-4 text-gray-700 font-semibold hover:bg-gray-50 transition" data-clear-wishlist>
            Hapus Semua Item
          </button>
        </div>
      </aside>
    </div>
  </main>

  @include('layouts.footer')
</body>
</html>