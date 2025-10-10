<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  {{-- NAMA PRODUK DINAMIS DI TITLE --}}
  <title>{{ $product->name }} — Detail Produk</title>
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
        <li><a href="{{ url('/') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li><a href="{{ route('catalog') }}" class="hover:text-gray-900">Katalog</a></li>
        <li class="text-gray-300">›</li>
        {{-- NAMA PRODUK DINAMIS --}}
        <li class="text-gray-900">{{ $product->name }}</li> 
      </ol>
    </nav>

    <div class="mt-6 rounded-2xl bg-white p-6 sm:p-8">
      <div class="grid grid-cols-12 gap-10">

        {{-- LEFT: Gambar besar --}}
        <div class="col-span-12 lg:col-span-6">
          <div class="rounded-2xl bg-[#f1f1f1] ring-1 ring-gray-200 p-8 grid place-items-center min-h-[420px]">
            @php
              // Ambil path gambar dari kolom 'image'. Pastikan path dimulai dengan '/'
              $imgUrl = $product->image ? '/' . $product->image : asset('images/placeholder.jpg'); 
              $fallback = asset('images/placeholder.jpg'); 
            @endphp
            <img
              {{-- GAMBAR DINAMIS --}}
              src="{{ $imgUrl }}" 
              alt="{{ $product->name }}"
              class="max-h-[420px] object-contain"
              onerror="this.onerror=null;this.src='{{ $fallback }}';"
            >
          </div>
        </div>

        {{-- RIGHT: Detail --}}
        <div class="col-span-12 lg:col-span-6">
          {{-- NAMA PRODUK DINAMIS --}}
          <h1 class="text-[34px] leading-tight font-extrabold">{{ $product->name }}</h1>

          <div class="mt-3 flex items-center gap-3">
            {{-- HARGA DINAMIS --}}
            <div class="text-3xl font-extrabold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
          </div>

          {{-- DESKRIPSI DINAMIS --}}
          <p class="mt-4 text-[13px] leading-6 text-gray-600 max-w-prose">
            {{ $product->description ?? 'Deskripsi produk ini belum tersedia. Silakan hubungi admin untuk informasi lebih lanjut.' }}
          </p>

          
          
          {{-- Stock DINAMIS --}}
          <div class="flex items-start gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-gray-100 ring-1 ring-gray-200">
              <i class="fa-solid fa-store text-gray-700"></i>
            </span>
            <div>
              <div class="text-sm font-medium text-gray-800">
                @if ($product->stock > 0)
                    Tersedia
                @else
                    Habis
                @endif
              </div>
              <div class="text-sm text-gray-500">Stok: {{ $product->stock }}</div>
            </div>
          </div>

          {{-- START: ATUR JUMLAH & SUBTOTAL --}}
          <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 mt-6" 
               data-product-price="{{ $product->price }}" data-product-stock="{{ $product->stock }}">
              <h4 class="text-lg font-bold mb-3">Atur jumlah dan catatan</h4>

              <div class="flex items-center justify-between">
                  {{-- Kontrol Kuantitas --}}
                  <div class="flex items-center overflow-hidden rounded-full border border-gray-300">
                      <button class="grid h-8 w-8 place-items-center hover:bg-gray-200 disabled:opacity-50" id="btn-qty-minus">
                          <i class="fa-solid fa-minus text-sm"></i>
                      </button>
                      <input type="number" id="input-qty" value="1" min="1" max="{{ $product->stock }}" 
                             class="w-12 text-center font-semibold border-none bg-transparent focus:ring-0 p-0" step="1">
                      <button class="grid h-8 w-8 place-items-center hover:bg-gray-200 disabled:opacity-50" id="btn-qty-plus">
                          <i class="fa-solid fa-plus text-sm"></i>
                      </button>
                  </div>
                  
                  {{-- Stok Total --}}
                  <span class="text-sm text-gray-600">Stok Total: Sisa <span class="font-bold text-orange-500">{{ $product->stock }}</span></span>
              </div>

              <div class="mt-4 flex items-center justify-between border-t border-gray-300 pt-3">
                  <span class="text-base font-semibold text-gray-700">Subtotal</span>
                  {{-- Subtotal awal akan dihitung oleh JS --}}
                  <span class="text-xl font-extrabold" id="subtotal-display">Rp {{ number_format($product->price, 0, ',', '.') }}</span> 
              </div>
          </div>
          {{-- END: ATUR JUMLAH & SUBTOTAL --}}

          {{-- Actions --}}
          <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <button id="btnWishlist" type="button"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    class="inline-flex h-11 flex-1 items-center justify-center rounded-md border border-gray-300 bg-white px-6 text-[15px] font-semibold hover:bg-gray-50">
              Add to Wishlist
            </button>

            <button id="btnCart" type="button"
                    data-id="{{ $product->id }}"
                    data-name="{{ $product->name }}"
                    data-price="{{ $product->price }}"
                    class="inline-flex h-11 flex-1 items-center justify-center rounded-md bg-black px-6 text-[15px] font-semibold text-white hover:bg-gray-900">
              Add to Cart
            </button>
          </div>

        </div>
      </div>
    </div>

  </main>

  @include('layouts.footer')

<script>
    // Helper function untuk format mata uang
    const formatCurrency = (amount) => {
        // Menggunakan Intl.NumberFormat dengan locale IDR
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    };

    document.addEventListener('DOMContentLoaded', function() {
        const priceElement = document.querySelector('[data-product-price]');
        const stockElement = document.querySelector('[data-product-stock]');

        // Pastikan elemen ditemukan dan data valid
        if (!priceElement || !stockElement) return;

        const price = parseFloat(priceElement.dataset.productPrice);
        const stock = parseInt(stockElement.dataset.productStock);
        
        const qtyInput = document.getElementById('input-qty');
        const minusBtn = document.getElementById('btn-qty-minus');
        const plusBtn = document.getElementById('btn-qty-plus');
        const subtotalDisplay = document.getElementById('subtotal-display');

        // Fungsi untuk mengupdate subtotal dan status tombol
        function updateSubtotal() {
            let qty = parseInt(qtyInput.value);
            
            // Logika validasi kuantitas
            if (isNaN(qty) || qty < 1) {
                qty = 1;
            } else if (qty > stock) {
                qty = stock; // Batasi maksimal stok
            }
            
            qtyInput.value = qty;
            const subtotal = qty * price;
            subtotalDisplay.textContent = formatCurrency(subtotal);
            
            // Nonaktifkan tombol jika batas stok/minimal tercapai
            minusBtn.disabled = qty <= 1;
            plusBtn.disabled = qty >= stock;
        }

        // Event Listener untuk input keyboard manual
        qtyInput.addEventListener('input', updateSubtotal); 
        
        // Event Listeners untuk tombol plus dan minus
        minusBtn.addEventListener('click', function() {
            let currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
                updateSubtotal();
            }
        });

        plusBtn.addEventListener('click', function() {
            let currentQty = parseInt(qtyInput.value);
            if (currentQty < stock) {
                qtyInput.value = currentQty + 1;
                updateSubtotal();
            }
        });
        
        // Inisialisasi tampilan awal
        updateSubtotal();
    });
</script>
</body>
</html>