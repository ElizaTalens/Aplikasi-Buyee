<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Your Cart — Buyee</title>
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
        <li class="text-gray-900">Cart</li>
      </ol>
    </nav>

    <h1 class="mt-3 text-[44px] font-extrabold tracking-tight">Your Cart</h1>

    <div class="mt-6 grid grid-cols-12 gap-8" data-cart>
      {{-- LEFT: items --}}
      <section class="col-span-12 lg:col-span-8">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 overflow-hidden">

          {{-- Row 1 --}}
          <div class="flex items-center gap-6 p-6" data-row data-price="145">
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

            <div class="flex items-center gap-4">
              <button class="text-rose-600 hover:text-rose-700" data-remove title="Remove">
                <i class="fa-solid fa-trash-can"></i>
              </button>

              <div class="flex h-11 items-center overflow-hidden rounded-full bg-gray-100 px-2">
                <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-minus>
                  <i class="fa-solid fa-minus"></i>
                </button>
                <span class="mx-3 w-6 text-center font-semibold" data-qty>1</span>
                <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-plus>
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <hr class="border-gray-200">

          {{-- Row 2 --}}
          <div class="flex items-center gap-6 p-6" data-row data-price="180">
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

            <div class="flex items-center gap-4">
              <button class="text-rose-600 hover:text-rose-700" data-remove title="Remove">
                <i class="fa-solid fa-trash-can"></i>
              </button>

              <div class="flex h-11 items-center overflow-hidden rounded-full bg-gray-100 px-2">
                <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-minus>
                  <i class="fa-solid fa-minus"></i>
                </button>
                <span class="mx-3 w-6 text-center font-semibold" data-qty>1</span>
                <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-plus>
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>
          </div>

          <hr class="border-gray-200">

          {{-- Row 3 --}}
          <div class="flex items-center gap-6 p-6" data-row data-price="240">
            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
              <img src="{{ asset('images/wmn.jpg') }}" alt="Skinny Fit Jeans" class="h-full w-full object-top">
            </div>

            <div class="min-w-0 flex-1">
              <h3 class="text-xl font-extrabold">Skinny Fit Jeans</h3>
              <div class="mt-1 text-sm text-gray-600 space-y-0.5">
                <div>Size: <span class="font-medium">Large</span></div>
                <div>Color: <span class="font-medium">Blue</span></div>
              </div>
              <div class="mt-3 text-2xl font-extrabold price">$240</div>
            </div>

            <div class="flex items-center gap-4">
              <button class="text-rose-600 hover:text-rose-700" data-remove title="Remove">
                <i class="fa-solid fa-trash-can"></i>
              </button>

              <div class="flex h-11 items-center overflow-hidden rounded-full bg-gray-100 px-2">
                <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-minus>
                  <i class="fa-solid fa-minus"></i>
                </button>
                <span class="mx-3 w-6 text-center font-semibold" data-qty>1</span>
                <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-plus>
                  <i class="fa-solid fa-plus"></i>
                </button>
              </div>
            </div>
          </div>

        </div>
      </section>

      {{-- RIGHT: summary --}}
      <aside class="col-span-12 lg:col-span-4">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-6">
          <h3 class="text-2xl font-extrabold">Order Summary</h3>

          <dl class="mt-6 space-y-4">
            <div class="flex items-center justify-between">
              <dt class="text-gray-600">Subtotal</dt>
              <dd id="subtotal" class="text-lg font-semibold">$0</dd>
            </div>
            <div class="flex items-center justify-between">
              <dt class="text-gray-600">Delivery Fee</dt>
              <dd id="delivery" class="text-lg font-semibold">$0</dd>
            </div>
            <hr class="border-gray-200">
            <div class="flex items-center justify-between">
              <dt class="text-gray-900 font-semibold">Total</dt>
              <dd id="grand" class="text-2xl font-extrabold">$0</dd>
            </div>
          </dl>

          <button
            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-900">
            Go to Checkout
            <i class="fa-solid fa-arrow-right-long ml-3"></i>
          </button>
        </div>
      </aside>
    </div>
  </main>

  @include('layouts.footer')
</body>
</html>
