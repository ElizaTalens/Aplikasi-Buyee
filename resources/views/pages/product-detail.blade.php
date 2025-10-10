<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Men Army Tee — Detail Produk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased">

  @include('layouts.navbar')

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">

    {{-- Breadcrumbs --}}
    <nav class="pt-30 text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="/" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li><a href="/" class="hover:text-gray-900">Detail Produk</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Men Army Tee</li>
      </ol>
    </nav>

    <div class="mt-6 rounded-2xl bg-white p-6 sm:p-8">
      <div class="grid grid-cols-12 gap-10">

        {{-- LEFT: Gambar besar --}}
        <div class="col-span-12 lg:col-span-6">
          <div class="rounded-2xl bg-[#f1f1f1] ring-1 ring-gray-200 p-8 grid place-items-center min-h-[420px]">
            @php
              // sekarang masih pakai gambar public; nanti tinggal ganti ke $product->image_url
              $img = asset('images/tee-men.jpg');
              $fallback = asset('images/placeholder.jpg'); // siapkan file ini di public/images
            @endphp
            <img
              src="{{ $img }}"
              alt="Men Army Tee"
              class="max-h-[420px] object-contain"
              onerror="this.onerror=null;this.src='{{ $fallback }}';"
            >
          </div>
        </div>

        {{-- RIGHT: Detail --}}
        <div class="col-span-12 lg:col-span-6">
          <h1 class="text-[34px] leading-tight font-extrabold">Men Army Tee</h1>

          <div class="mt-3 flex items-center gap-3">
            <div class="text-3xl font-extrabold">$100</div>
          </div>

          <p class="mt-4 text-[13px] leading-6 text-gray-600 max-w-prose">
            Enhanced capabilities thanks to an enlarged display of 6.7 inches and work without
            recharging throughout the day. Incredible photos in weak and bright light using the new
            system with two cameras.
          </p>

          {{-- Select Colors --}}
          <div class="mt-6">
            <div class="text-sm font-medium text-gray-800">Select Colors</div>

            {{-- hidden value buat backend nanti --}}
            <input type="hidden" name="color" id="colorInput" value="brown">

            <div id="colorGroup" role="radiogroup" class="mt-3 flex items-center gap-3">
              <button type="button" role="radio" aria-checked="true"
                      data-color="brown"
                      class="h-7 w-7 rounded-full bg-[#5d4e33] ring-2 ring-black ring-offset-2"
                      title="Brown"></button>

              <button type="button" role="radio" aria-checked="false"
                      data-color="green"
                      class="h-7 w-7 rounded-full bg-[#3a564b] ring-1 ring-gray-300 ring-offset-0"
                      title="Green"></button>

              <button type="button" role="radio" aria-checked="false"
                      data-color="navy"
                      class="h-7 w-7 rounded-full bg-[#2f3f59] ring-1 ring-gray-300 ring-offset-0"
                      title="Navy"></button>
            </div>
          </div>

          <div class="my-6 border-b border-gray-200"></div>

          {{-- Choose Size --}}
          <div>
            <div class="text-sm font-medium text-gray-800">Choose Size</div>

            {{-- hidden value buat backend nanti --}}
            <input type="hidden" name="size" id="sizeInput" value="L">

            <div id="sizeGroup" role="radiogroup" class="mt-3 flex flex-wrap gap-3">
              <button type="button" role="radio" aria-checked="false"
                      data-size="S"
                      class="inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700">
                Small
              </button>

              <button type="button" role="radio" aria-checked="false"
                      data-size="M"
                      class="inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700">
                Medium
              </button>

              <button type="button" role="radio" aria-checked="true"
                      data-size="L"
                      class="inline-flex h-10 items-center rounded-full bg-black px-5 text-sm font-semibold text-white">
                Large
              </button>

              <button type="button" role="radio" aria-checked="false"
                      data-size="XL"
                      class="inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700">
                X-Large
              </button>
            </div>
          </div>

          {{-- Stock --}}
          <div class="mt-6 flex items-start gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-gray-100 ring-1 ring-gray-200">
              <i class="fa-solid fa-store text-gray-700"></i>
            </span>
            <div>
              <div class="text-sm font-medium text-gray-800">In Stock</div>
              <div class="text-sm text-gray-500">Today</div>
            </div>
          </div>

          {{-- Actions --}}
          <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <button id="btnWishlist" type="button"
                    data-sku="men-army-tee"
                    data-name="Men Army Tee"
                    data-price="100"
                    class="inline-flex h-11 flex-1 items-center justify-center rounded-md border border-gray-300 bg-white px-6 text-[15px] font-semibold hover:bg-gray-50">
              Add to Wishlist
            </button>

            <button id="btnCart" type="button"
                    data-sku="men-army-tee"
                    data-name="Men Army Tee"
                    data-price="100"
                    class="inline-flex h-11 flex-1 items-center justify-center rounded-md bg-black px-6 text-[15px] font-semibold text-white hover:bg-gray-900">
              Add to Cart
            </button>
          </div>

        </div>
      </div>
    </div>

  </main>

  @include('layouts.footer')

</body>
</html>
