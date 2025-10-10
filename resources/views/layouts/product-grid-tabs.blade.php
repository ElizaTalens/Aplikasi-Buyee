<section>
  {{-- Tabs --}}
  <div class="flex items-center gap-6 border-b border-gray-200 text-[13px]">
    <button class="relative text-lg pb-3 font-semibold text-gray-900 after:absolute after:inset-x-0 after:-bottom-[1px] after:h-0.5 after:bg-gray-900">
      New Arrival
    </button>
    <button class="pb-3 text-lg text-black-500 hover:text-gray-400">Bestseller</button>
  </div>

  <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3 lg:grid-cols-4">

  {{-- Card 1 --}}
  <a href="{{ route('product.detail', 'skin1004-centella-ampoule') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}"   
        alt="SKIN1004 Madagascar Centella Ampoule 100ml"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">SKIN1004</p>
      <p class="line-clamp-2 text-sm text-gray-600">Madagascar Centella Ampoule 100ml</p>
      <p class="text-[15px] font-bold">Rp 399.000</p> 
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">
        Buy Now
      </span>
    </div>
  </a>

  {{-- Card 2 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}"         
        alt="Men T-Shirt Heavy Cotton"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Heavy Cotton Tee</p>
      <p class="line-clamp-2 text-sm text-gray-600">Premium 220 GSM</p>
      <p class="text-[15px] font-bold">Rp 149.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 3 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}"         
        alt="Women Shoulder Bag"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Vegan Leather Bag</p>
      <p class="line-clamp-2 text-sm text-gray-600">Compact Shoulder Bag</p>
      <p class="text-[15px] font-bold">Rp 259.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 4 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 5 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 6 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 7 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}"
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 8 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 9 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 10 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 11 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}"
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>

  {{-- Card 12 --}}
  <a href="{{ route('product.detail', 'heavy-cotton-tee') }}" class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
    <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
      <i class="fa-regular fa-heart fa-lg"></i>
    </button>
    <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
      <img
        src="{{ asset('images/electronics.jpg') }}" 
        alt="Wireless Charger 15W"
        class="h-full w-full object-top"
        loading="lazy">
    </div>
    <div class="mt-3 space-y-1">
      <p class="line-clamp-2 text-lg font-bold text-gray-900">Wireless Charger</p>
      <p class="line-clamp-2 text-sm text-gray-600">15W Fast Charge</p>
      <p class="text-[15px] font-bold">Rp 179.000</p>
    </div>
    <div class="mt-3">
      <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">Buy Now</span>
    </div>
  </a>
</div>

  {{-- View all --}}
  <div class="mt-8 flex justify-center">
    <a href="#" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-5 text-sm font-semibold hover:bg-gray-50">
      View All
    </a>
  </div>
</section>