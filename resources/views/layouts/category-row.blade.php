<section>
  <div class="flex items-center justify-between">
    <h3 class="text-lg font-semibold">Browse By Category</h3>

    <div class="flex items-center gap-2">
      <button id="cat-prev"
        class="grid h-9 w-9 place-items-center rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
        aria-label="Scroll left">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <button id="cat-next"
        class="grid h-9 w-9 place-items-center rounded-full border border-gray-200 bg-white text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed"
        aria-label="Scroll right">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>
  </div>

  <div id="cat-track"
       class="mt-5 flex gap-4 overflow-x-auto overscroll-x-contain snap-x snap-mandatory scroll-smooth
              [scrollbar-width:none] [-ms-overflow-style:none]"  <!-- Firefox/IE hide -->
    <style>
      #cat-track::-webkit-scrollbar { display: none; }
    </style>

    {{-- CARD TEMPLATE --}}
    <a href="{{ route('catalog') }}" class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
      <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
        <img src="{{ asset('images/carts.jpg') }}" alt="Women Fashion" class="h-full w-full object-top" loading="lazy" />
      </div>
      <div class="text-base font-semibold text-gray-700">All Products</div>
    </a>

    <a href="{{ route('catalog') }}"
       class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
      <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
        <img src="{{ asset('images/wmn.jpg') }}" alt="Women Fashion" class="h-full w-full object-top" loading="lazy" />
      </div>
      <div class="text-base font-semibold text-gray-700">Women Fashion</div>
    </a>

    <a href="{{ route('catalog') }}"
       class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
      <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
        <img src="{{ asset('images/men.jpg') }}" alt="Men Fashion" class="h-full w-full object-top" loading="lazy" />
      </div>
      <div class="text-base font-semibold text-gray-700">Men Fashion</div>
    </a>

    <a href="{{ route('catalog') }}"
       class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
      <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
        <img src="{{ asset('images/skincare.jpg') }}" alt="Kids Fashion class="h-full w-full object-top" loading="lazy" />
      </div>
      <div class="text-base font-semibold text-gray-700">Kids Fashion</div>
    </a>

    <a href="{{ route('catalog') }}"
       class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
      <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
        <img src="{{ asset('images/health.jpg') }}" alt="Accessories" class="h-full w-full object-top" loading="lazy" />
      </div>
      <div class="text-base font-semibold text-gray-700">Accessories</div>
    </a>

    <a href="{{ route('catalog') }}"
       class="min-w-[210px] snap-start group rounded-xl border border-gray-200 bg-white p-4 text-center hover:shadow-card transition">
      <div class="mx-auto mb-3 h-28 w-28 overflow-hidden rounded-lg bg-gray-50 ring-1 ring-gray-200">
        <img src="{{ asset('images/st.jpg') }}" alt="Shoes" class="h-full w-full object-top" loading="lazy" />
      </div>
      <div class="text-base font-semibold text-gray-700">Shoes</div>
    </a>
  </div>
</section>