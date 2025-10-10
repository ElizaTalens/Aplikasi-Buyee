<section>
    {{-- Tabs --}}
    <div class="flex items-center gap-6 border-b border-gray-200 text-[13px]">
        <button class="relative text-lg pb-3 font-semibold text-gray-900 after:absolute after:inset-x-0 after:-bottom-[1px] after:h-0.5 after:bg-gray-900">
            New Arrival
        </button>
        <button class="pb-3 text-lg text-black-500 hover:text-gray-400">Bestseller</button>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3 lg:grid-cols-4">

        @foreach ($products as $product)
        
        <a href="{{ route('product.detail', $product->id) }}" 
           class="group relative rounded-xl border border-gray-200 bg-white p-4 hover:shadow-card transition">
            
            <button class="absolute right-3 top-3 p-1.5 text-gray-300 hover:text-rose-500" aria-label="Like">
                <i class="fa-regular fa-heart fa-lg"></i>
            </button>
            
            <div class="aspect-[4/3] overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-200">
                @php
                    // Pastikan path image selalu dimulai dengan '/' agar diakses dari root public
                    $imageUrl = $product->image ? '/' . $product->image : asset('images/placeholder.jpg'); 
                @endphp
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $product->name }}"
                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105"
                    loading="lazy"
                    {{-- Fallback jika gambar tidak ditemukan --}}
                    onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';" 
                >
            </div>
            
            <div class="mt-3 space-y-1">
                {{-- Nama Produk --}}
                <p class="line-clamp-2 text-lg font-bold text-gray-900">{{ $product->name }}</p>
                
                {{-- Kategori (Tampilkan nama kategori jika relasi ada) --}}
                <p class="line-clamp-2 text-sm text-gray-600">{{ $product->category->name ?? 'Tanpa Kategori' }}</p> 
                
                {{-- Harga --}}
                <p class="text-[15px] font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
            
            {{-- <div class="mt-3">
                <span class="inline-flex h-8 items-center justify-center rounded-md bg-gray-900 px-3 text-[11px] font-semibold text-white hover:bg-white hover:text-[#2a242b] transition-colors">
                    Buy Now
                </span>
            </div> --}}
        </a>

        @endforeach
        </div>

    {{-- View all --}}
    <div class="mt-8 flex justify-center">
        <a href="{{ route('catalog') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-5 text-sm font-semibold hover:bg-gray-50">
            View All
        </a>
    </div>
</section>