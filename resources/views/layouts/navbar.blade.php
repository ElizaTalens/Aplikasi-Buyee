<header class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-20 flex items-center gap-6">
    {{-- Logo --}}
    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
      <img src="{{ asset('images/logo.png') }}" alt="Buyee" class="h-8 w-auto">
    </a>

    {{-- Search --}}
    <div class="flex-1">
      <div class="relative">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" aria-hidden="true"></i>
        <input
          class="w-full h-12 rounded-md border border-gray-200 bg-gray-50 pl-10 pr-4 text-lg outline-none
                 focus:border-gray-300 focus:ring-2 focus:ring-gray-200"
          placeholder="Search" />
      </div>
    </div>

    {{-- Nav menu --}}
    <nav class="hidden md:flex items-center gap-6 text-lg">
      <a href="{{ route('home') }}" class="font-semibold text-gray-900 hover:text-gray-500">Home</a>
      
      {{-- FIX: Catalog mengarah ke login jika belum auth --}}
      @auth
        <a href="{{ route('catalog') }}" class="font-semibold text-gray-900 hover:text-gray-500">Catalog</a>
      @else
        <a href="{{ route('login.form') }}" class="font-semibold text-gray-900 hover:text-gray-500">Catalog</a>
      @endauth
    </nav>

    {{-- Icons --}}
    @php
      $wishTotal = session('wishlist') ? count(session('wishlist')) : 0;
      $cartTotal = session('cart') ? count(session('cart')) : 0;
    @endphp

    {{-- Wishlist --}}
    @auth
      <a href="{{ route('wishlist') }}" class="relative" aria-label="Wishlist">
        <i class="fa-regular fa-heart fa-xl hover:text-gray-500"></i>
        <span id="wishlistCount"
              class="absolute -right-2 -top-2 {{ $wishTotal ? '' : 'hidden' }} min-w-[18px] rounded-full bg-rose-600 px-1.5 text-[11px] font-bold text-white text-center">
          {{ $wishTotal ?: '' }}
        </span>
      </a>
    @else
      <a href="{{ route('login.form') }}" class="relative" aria-label="Wishlist (login dulu)">
        <i class="fa-regular fa-heart fa-xl hover:text-gray-500"></i>
        <span id="wishlistCount"
              class="absolute -right-2 -top-2 {{ $wishTotal ? '' : 'hidden' }} min-w-[18px] rounded-full bg-rose-600 px-1.5 text-[11px] font-bold text-white text-center">
          {{ $wishTotal ?: '' }}
        </span>
      </a>
    @endauth

    {{-- Cart --}}
    @auth
      <a href="{{ route('cart') }}" class="relative" aria-label="Cart">
        <i class="fa-solid fa-cart-shopping fa-xl hover:text-gray-500"></i>
        <span id="cartCount"
              class="absolute -right-2 -top-2 {{ $cartTotal ? '' : 'hidden' }} min-w-[18px] rounded-full bg-black px-1.5 text-[11px] font-bold text-white text-center">
          {{ $cartTotal ?: '' }}
        </span>
      </a>
    @else
      <a href="{{ route('login.form') }}" class="relative" aria-label="Cart (login dulu)">
        <i class="fa-solid fa-cart-shopping fa-xl hover:text-gray-500"></i>
        <span id="cartCount"
              class="absolute -right-2 -top-2 {{ $cartTotal ? '' : 'hidden' }} min-w-[18px] rounded-full bg-black px-1.5 text-[11px] font-bold text-white text-center">
          {{ $cartTotal ?: '' }}
        </span>
      </a>
    @endauth

    {{-- User (Dropdown dengan Hover Fix) --}}
    <div class="relative group">
        {{-- Tautan Ikon User --}}
        @auth
            <a href="{{ route('profile.index') }}" class="p-2 hover:text-gray-900" aria-label="Profil/Dashboard">
                <i class="fa-solid fa-user fa-xl hover:text-gray-500"></i>
            </a>
        @else
            <a href="{{ route('login.form') }}" class="p-2 hover:text-gray-900" aria-label="Login / Register">
                <i class="fa-solid fa-user fa-xl hover:text-gray-500"></i>
            </a>
        @endauth

        {{-- Dropdown Menu (Menggunakan mt-1 dan pt-3 untuk stabilitas hover) --}}
        <div class="absolute right-0 mt-1 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-gray-200 
                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition duration-200 ease-out 
                    transform translate-y-2 group-hover:translate-y-0 pt-3"
            style="z-index: 1000; min-width: 200px;">
            
            <div class="p-2 space-y-1">
                @auth
                    {{-- Opsi: Profile Saya --}}
                    <a href="{{ route('profile.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <i class="fa-solid fa-user mr-3 text-lg text-indigo-500"></i>
                        <span class="font-semibold">Profile Saya</span>
                    </a>
                    
                    {{-- FIX: Logout menggunakan FORM POST --}}
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-3 rounded-lg hover:bg-red-50 text-red-600 font-semibold transition">
                            <i class="fa-solid fa-sign-out-alt mr-3 text-lg"></i>
                            Keluar
                        </button>
                    </form>

                @else
                    {{-- Opsi: Masuk/Daftar --}}
                    <a href="{{ route('login.form') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <i class="fa-solid fa-sign-in-alt mr-3 text-lg text-green-500"></i>
                        <span class="font-semibold">Masuk / Daftar</span>
                    </a>
                    
                    {{-- Opsi: Bantuan --}}
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <i class="fa-solid fa-question-circle mr-3 text-lg text-blue-500"></i>
                        <span class="font-semibold">Pusat Bantuan</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
  </div>
</header>