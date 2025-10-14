{{-- resources/views/layouts/navbar.blade.php --}}
<header class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-100">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-20 flex items-center gap-6">
    <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
      <img src="{{ asset('images/logo.png') }}" alt="Buyee" class="h-8 w-auto">
    </a>
    

    <div class="flex-1">
      <form action="{{ route('products.search') }}" method="GET" class="relative group">
        <button type="submit" class="absolute left-0 top-0 h-0 w-0 overflow-hidden" tabindex="-1" aria-hidden="true"></button>
        <i class="fa-solid fa-magnifying-glass absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
        <input
          name="q"
          type="search"
          class="w-full h-12 rounded-md border border-gray-200 bg-gray-50 pl-10 pr-4 text-lg outline-none focus:border-gray-300 focus:ring-2 focus:ring-gray-200"
          placeholder="Cari di Buyee..."
          value="{{ request('q', request('search')) }}"
          autocomplete="off" 
        />
        
        {{-- Dropdown untuk History Pencarian --}}
        @if(isset($searchHistory) && !empty($searchHistory))
        <div class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-lg border border-gray-200 z-20 hidden group-focus-within:block group-hover:block">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-600">Pencarian Terakhir</h4>
                    @if(count($searchHistory) > 1)
                        <button type="submit" name="clear_history" value="all" class="text-xs font-semibold text-rose-500 hover:text-rose-600">Hapus semua</button>
                    @endif
                </div>
                <ul class="mt-2 space-y-1">
                    @foreach ($searchHistory as $term)
                    <li class="flex items-center justify-between gap-2 rounded-lg px-2 hover:bg-gray-50">
                        <button type="submit" name="selected_history" value="{{ $term }}" class="flex flex-1 items-center gap-3 py-2 text-left">
                            <i class="fa-solid fa-history text-gray-400"></i>
                            <span class="text-sm text-gray-800">{{ $term }}</span>
                        </button>
                        <button type="submit" name="remove_history" value="{{ $term }}" class="text-xs font-semibold text-gray-400 hover:text-rose-500 transition" aria-label="Hapus pencarian '{{ $term }}'">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
      </form>
    </div>

    {{-- Nav menu --}}
    <nav class="hidden md:flex items-center gap-6 text-lg">
      <a href="{{ route('home') }}" class="font-semibold text-gray-900 hover:text-gray-500">Home</a>
      
      {{-- FIX: Catalog mengarah ke login jika belum auth --}}
      @auth
        <a href="{{ route('catalog') }}" class="font-semibold text-gray-900 hover:text-gray-500">Katalog</a>
      @else
        <a href="{{ route('login') }}" class="font-semibold text-gray-900 hover:text-gray-500">Katalog</a>
      @endauth
    </nav>

    {{-- Icons --}}
    {{-- Wishlist --}}
    @auth
      <a href="{{ route('wishlist.index') }}" class="relative inline-block w-6 h-6" aria-label="Wishlist">
        <i class="fa-regular fa-heart fa-xl hover:text-gray-500"></i>
        <span id="wishlistCount"
              data-sync="server"
              class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 {{ $wishlistCount > 0 ? '' : 'hidden' }} min-w-[18px] h-[18px] rounded-full bg-rose-600 px-1 text-[11px] font-bold text-white flex items-center justify-center pointer-events-none z-20">
          {{ $wishlistCount }}
        </span>
      </a>
    @else
      <a href="{{ route('login') }}" class="relative inline-block w-6 h-6" aria-label="Wishlist (login dulu)">
        <i class="fa-regular fa-heart fa-xl hover:text-gray-500"></i>
        <span id="wishlistCount"
              data-sync="local"
              class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 hidden min-w-[18px] h-[18px] rounded-full bg-rose-600 px-1 text-[11px] font-bold text-white flex items-center justify-center pointer-events-none z-20">
          0
        </span>
      </a>
    @endauth

    {{-- Cart --}}
    @auth
      <a href="{{ route('cart.index') }}" class="relative inline-block w-6 h-6" aria-label="Cart">
        <i class="fa-solid fa-cart-shopping fa-xl hover:text-gray-500"></i>
        <span id="cartCount"
              data-sync="server"
              data-server-count="{{ $cartCount }}"
              class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 {{ $cartCount > 0 ? '' : 'hidden' }} min-w-[18px] h-[18px] rounded-full bg-black px-1 text-[11px] font-bold text-white flex items-center justify-center pointer-events-none z-20">
          {{ $cartCount }}
        </span>
      </a>
    @else
      <a href="{{ route('login') }}" class="relative inline-block w-6 h-6" aria-label="Cart (login dulu)">
        <i class="fa-solid fa-cart-shopping fa-xl hover:text-gray-500"></i>
        <span id="cartCountGuest"
              data-sync="local"
              class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2 hidden min-w-[18px] h-[18px] rounded-full bg-black px-1 text-[11px] font-bold text-white flex items-center justify-center pointer-events-none z-20">
          0
        </span>
      </a>
    @endauth

    {{-- User (Dropdown dengan Hover Fix) --}}
    <div class="relative group">
        {{-- Tautan Ikon User --}}
        @auth
            <a href="#" class="p-2 hover:text-gray-900" aria-label="Profil/Dashboard">
                <i class="fa-solid fa-user fa-xl hover:text-gray-500"></i>
            </a>
        @else
            <a href="{{ route('login') }}" class="p-2 hover:text-gray-900" aria-label="Login / Register">
                <i class="fa-solid fa-user fa-xl hover:text-gray-500"></i>
            </a>
        @endauth

        {{-- Dropdown Menu (Hidden by default, shown on hover) --}}
        <div class="absolute right-0 mt-1 w-56 rounded-xl shadow-2xl bg-white ring-1 ring-gray-200 
                    opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 ease-out pt-3"
            style="z-index: 1000; min-width: 200px;">
            
            <div class="p-2 space-y-1">
                @auth
                    {{-- Opsi: Profile Saya --}}
                    <a href="{{ route('profile.edit') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <i class="fa-solid fa-user mr-3 text-gray-500"></i>
                        <span class="font-semibold">Profile Saya</span>
                    </a>
                    
                    {{-- Opsi: Riwayat Pesanan --}}
                    <a href="{{ route('orders.index') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <i class="fa-solid fa-shopping-bag mr-3 text-gray-500"></i>
                        <span class="font-semibold">Riwayat Pesanan</span>
                    </a>
                    
                    {{-- FIX: Logout menggunakan FORM POST --}}
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center w-full p-3 rounded-lg hover:bg-red-50 text-red-600 font-semibold transition">
                            <i class="fa-solid fa-sign-out-alt mr-3 text-red-500"></i>
                            Keluar
                        </button>
                    </form>

                @else
                    {{-- Opsi: Masuk/Daftar --}}
                    <a href="{{ route('login') }}" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <span class="font-semibold">Masuk / Daftar</span>
                    </a>
                    
                    {{-- Opsi: Bantuan --}}
                    <a href="#" class="flex items-center p-3 rounded-lg hover:bg-gray-50 text-gray-800 transition">
                        <span class="font-semibold">Pusat Bantuan</span>
                    </a>
                @endauth
            </div>
        </div>
    </div>
  </div>
</header>
