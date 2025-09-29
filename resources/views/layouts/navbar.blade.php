<header class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-20 flex items-center gap-6">
    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2 -ml-35 shrink-0">
      <img src="{{ asset('images/logo.png') }}" alt="Buyee" class="h-30 w-auto"> {{-- ganti path sesuai logomu --}}
    </a>

    {{-- Search --}}
    <div class="flex-1 -ml-2">
      <div class="relative ">
        <i class="fa-solid fa-magnifying-glass
              absolute left-5 top-1/2 -translate-y-1/2
              text-gray-400 pointer-events-none" aria-hidden="true"></i>
        <input
        class="w-full h-12 rounded-md border border-gray-200 bg-gray-50
              px-4 pl-10 text-lg outline-none
              focus:border-gray-300 focus:ring-2 focus:ring-gray-200"
        placeholder="Search" />
      </div>
    </div>

    {{-- Nav menu --}}
    <nav class="hidden md:flex items-center gap-6 text-lg ml-3">
      <a href="/" class="font-semibold text-black-900 hover:text-gray-500">Home</a>
    </nav>

    {{-- Icons --}}
    @php
      $wishTotal = session('wishlist') ? count(session('wishlist')) : 0;
      $cartTotal = session('cart') ? count(session('cart')) : 0;
    @endphp

    <a href="#" class="relative" aria-label="Wishlist">
      <i class="fa-regular fa-heart fa-xl  hover:text-gray-500"></i>
      <span id="wishCount" class="absolute -right-2 -top-2 {{ $wishTotal ? '' : 'hidden' }} min-w-[18px] rounded-full bg-rose-600 px-1.5 text-[11px] font-bold text-white text-center">
        {{ $wishTotal ?: '' }}
      </span>
    </a>

   <a href="{{ route('cart') }}" class="relative" aria-label="Cart">
      <i class="fa-solid fa-cart-shopping fa-xl  hover:text-gray-500"></i>
      <span id="cartCount" class="absolute -right-2 -top-2 {{ $cartTotal ? '' : 'hidden' }} min-w-[18px] rounded-full bg-black px-1.5 text-[11px] font-bold text-white text-center">
        {{ $cartTotal ?: '' }}
      </span>
    </a>
      <button class="p-2 hover:text-gray-900" aria-label="User">
        <i class="fa-solid fa-user fa-xl hover:text-gray-500"></i>
      </button>
    </div>
  </div>
</header>
