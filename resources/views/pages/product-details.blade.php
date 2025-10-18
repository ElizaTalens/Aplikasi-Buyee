<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>{{ $product->name }} — Detail Produk</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    /* Sembunyikan panah input angka */
    .no-arrows::-webkit-outer-spin-button,
    .no-arrows::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    .no-arrows[type=number] {
      -moz-appearance: textfield;
    }
  </style>
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased">

  @include('layouts.navbar')

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-16">

    {{-- Breadcrumbs --}}
    <nav class="pt-30 text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="/" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li><a href="{{ route('catalog') }}" class="hover:text-gray-900">Produk</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">{{ $product->name }}</li>
      </ol>
    </nav>

    <div class="mt-6 rounded-2xl bg-white p-6 sm:p-8">
      <div class="grid grid-cols-12 gap-10">

        {{-- KIRI: Gambar Produk --}}
        <div class="col-span-12 lg:col-span-6">
          <div class="rounded-2xl bg-[#f1f1f1] ring-1 ring-gray-200 p-8 grid place-items-center min-h-[420px]">
            @php
              $img = (isset($product->images[0])) ? asset('storage/' . $product->images[0]) : asset('images/placeholder.jpg');
              $fallback = asset('images/placeholder.jpg');
            @endphp
            <img
              src="{{ $img }}"
              alt="{{ $product->name }}"
              class="max-h-[420px] object-contain"
              onerror="this.onerror=null;this.src='{{ $fallback }}';"
            >
          </div>
        </div>

        {{-- KANAN: Detail Produk --}}
        <div class="col-span-12 lg:col-span-6">
          <h1 class="text-[34px] leading-tight font-extrabold">{{ $product->name }}</h1>

          <div class="mt-3 flex items-center gap-3">
            <div class="text-3xl font-extrabold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
          </div>

          <p class="mt-4 text-[13px] leading-6 text-gray-600 max-w-prose">
            {{ $product->description ?? 'Deskripsi produk belum tersedia.' }}
          </p>

          {{-- Pilih Ukuran --}}
          <div>
            <div class="pt-5 *:text-sm font-medium text-gray-800">Pilih Ukuran</div>
            <input type="hidden" name="size" id="sizeInput" value="L">
            <div id="sizeGroup" role="radiogroup" class="mt-3 flex flex-wrap gap-3">
              <button type="button" role="radio" aria-checked="false" data-size="S" class="inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700">Small</button>
              <button type="button" role="radio" aria-checked="false" data-size="M" class="inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700">Medium</button>
              <button type="button" role="radio" aria-checked="true" data-size="L" class="inline-flex h-10 items-center rounded-full bg-black px-5 text-sm font-semibold text-white">Large</button>
              <button type="button" role="radio" aria-checked="false" data-size="XL" class="inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700">X-Large</button>
            </div>
          </div>

          {{-- Stok --}}
          <div class="mt-6 flex items-start gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-gray-100 ring-1 ring-gray-200">
              <i class="fa-solid fa-store text-gray-700"></i>
            </span>
            <div>
              <div class="text-sm font-medium text-gray-800">
                @if($product->stock_quantity > 0)
                  Stok Tersedia ({{ $product->stock_quantity }} buah)
                @else
                  Stok Habis
                @endif
              </div>
              <div class="text-sm text-gray-500">Informasi terbaru</div>
            </div>
          </div>

          {{-- Pilihan Kuantitas --}}
          <div class="mt-6">
            <div class="text-sm font-medium text-gray-800">Kuantitas</div>
            <div class="mt-3 flex items-center gap-3">
              <button type="button" id="decreaseQty" class="flex h-10 w-10 items-center justify-center rounded-md border border-gray-300 bg-white hover:bg-gray-50">
                <i class="fa-solid fa-minus text-sm"></i>
              </button>
              <input type="number" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" class="w-20 text-center border border-gray-300 rounded-md h-10 no-arrows">
              <button type="button" id="increaseQty" class="flex h-10 w-10 items-center justify-center rounded-md border border-gray-300 bg-white hover:bg-gray-50">
                <i class="fa-solid fa-plus text-sm"></i>
              </button>
            </div>
          </div>

          {{-- Tombol Aksi --}}
          <div class="mt-8 flex flex-col sm:flex-row gap-4">
            <button id="btnWishlist" type="button" onclick="addToWishlist({{ $product->id }})" class="inline-flex h-11 flex-1 items-center justify-center rounded-md border border-gray-300 bg-white px-6 text-[15px] font-semibold hover:bg-gray-50">
              <i class="fa-regular fa-heart mr-2"></i>
              Tambah ke Wishlist
            </button>

            <button id="btnCart" type="button" onclick="addToCart({{ $product->id }})" @if($product->stock_quantity <= 0) disabled @endif class="inline-flex h-11 flex-1 items-center justify-center rounded-md bg-black px-6 text-[15px] font-semibold text-white hover:bg-gray-900 disabled:bg-gray-400 disabled:cursor-not-allowed">
              @if($product->stock_quantity > 0)
                <i class="fa-solid fa-cart-shopping mr-2"></i>
                Tambah ke Keranjang
              @else
                Stok Habis
              @endif
            </button>
          </div>

        </div>
      </div>
    </div>
  </main>

  @include('layouts.footer')

  {{-- ================================================================= --}}
  {{-- =================== BLOK JAVASCRIPT LENGKAP =================== --}}
  {{-- ================================================================= --}}
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // --- Pemilihan Ukuran ---
      const sizeGroup = document.getElementById('sizeGroup');
      if(sizeGroup) {
        sizeGroup.addEventListener('click', function(e) {
          const button = e.target.closest('button[data-size]');
          if (!button) return;

          // Hapus status aktif dari semua tombol
          document.querySelectorAll('#sizeGroup button').forEach(btn => {
            btn.setAttribute('aria-checked', 'false');
            btn.className = 'inline-flex h-10 items-center rounded-full bg-gray-100 px-5 text-sm text-gray-700';
          });
          
          // Tambahkan status aktif ke tombol yang diklik
          button.setAttribute('aria-checked', 'true');
          button.className = 'inline-flex h-10 items-center rounded-full bg-black px-5 text-sm font-semibold text-white';
          
          // Update nilai input tersembunyi
          document.getElementById('sizeInput').value = button.dataset.size;
        });
      }

      // --- Kontrol Kuantitas ---
      const qtyInput = document.getElementById('quantity');
      const maxQty = parseInt(qtyInput.getAttribute('max'));

      document.getElementById('decreaseQty').addEventListener('click', function() {
        const currentQty = parseInt(qtyInput.value);
        if (currentQty > 1) {
          qtyInput.value = currentQty - 1;
        }
      });

      document.getElementById('increaseQty').addEventListener('click', function() {
        const currentQty = parseInt(qtyInput.value);
        if (currentQty < maxQty) {
          qtyInput.value = currentQty + 1;
        }
      });
    });

    /**
     * Menampilkan notifikasi toast sederhana.
     * @param {string} msg - Pesan yang akan ditampilkan.
     * @param {string} type - Tipe notifikasi ('ok' atau 'error').
     */
    function toast(msg, type = 'ok') {
      document.querySelectorAll('.app-toast').forEach(el => el.remove());

      const t = document.createElement('div');
      t.textContent = msg;
      t.className =
        'app-toast fixed left-1/2 top-6 -translate-x-1/2 z-[9999] rounded-md px-4 py-2 text-sm font-semibold shadow-lg ' +
        (type === 'ok' ? 'bg-black text-white' : 'bg-rose-600 text-white');
      document.body.appendChild(t);
      setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity .3s'; }, 1800);
      setTimeout(() => t.remove(), 2200);
    }

    /**
     * Memperbarui badge counter keranjang di navbar.
     * @param {number} count - Jumlah item baru di keranjang.
     */
    function updateCartBadge(count) {
      const updated = typeof updateCount === 'function'
        ? updateCount('cartCount', count, { force: true })
        : null;
      if (!updated) {
        console.error('Elemen counter keranjang tidak ditemukan untuk diperbarui.');
      }
    }

    /**
     * Mengirim data produk ke server untuk ditambahkan ke keranjang.
     * @param {number} productId - ID produk yang akan ditambahkan.
     */
    async function addToCart(productId) {
      const quantity = document.getElementById('quantity').value;
      const size = document.getElementById('sizeInput').value;
      const btn = document.getElementById('btnCart');
      
      // Simpan teks asli tombol dan tampilkan spinner
      const originalButtonHTML = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `<i class="fa-solid fa-spinner fa-spin mr-2"></i> Menambahkan...`;

      try {
        const response = await fetch("{{ route('cart.store') }}", {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            product_id: productId,
            quantity: parseInt(quantity),
            options: {
              size: size
            }
          })
        });
        if (response.status === 401) {
          // Arahkan pengguna ke halaman login jika belum login
          window.location.href = "{{ route('login') }}"; 
          return; 
        }
        const data = await response.json();

        if (response.ok) {
          toast('Produk berhasil ditambahkan!', 'ok');
          
          // **FIX UTAMA**: Update badge dengan jumlah dari respons server
          if (typeof data.cart_count !== 'undefined') {
            updateCartBadge(data.cart_count);
          }
        } else {
          // Tampilkan pesan error dari server jika ada
          toast(data.message || 'Gagal menambahkan produk.', 'error');
        }
      } catch (error) {
        console.error('Error:', error);
        toast('Terjadi kesalahan. Coba lagi nanti.', 'error');
      } finally {
        // Kembalikan tombol ke keadaan semula
        btn.disabled = false;
        btn.innerHTML = originalButtonHTML;
      }
    }

    /**
     * Menambahkan atau menghapus produk dari wishlist.
     * @param {number} productId - ID produk.
     */
    async function addToWishlist(productId) {
      try {
        const response = await fetch("{{ route('wishlist.toggle') }}", { // Gunakan route toggle untuk wishlist
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify({
            product_id: productId
          })
        });
        if (response.status === 401) {
            // Tambahkan parameter 'intended' ke URL login
            window.location.href = "{{ route('login') }}?redirect_to=" + encodeURIComponent(window.location.href); 
            return;
        }
        const data = await response.json();
        if (response.ok) {
          toast(data.message, 'ok');

          if (typeof updateCount === 'function' && typeof data.count !== 'undefined') {
            updateCount('wishlistCount', data.count, { force: true });
          } else if (typeof updateWishlistCountFromDB === 'function') {
            updateWishlistCountFromDB();
          }

        } else {
          toast(data.message || 'Gagal.', 'error');
        }
      } catch(error) {
        toast('Terjadi kesalahan pada wishlist.', 'error');
      }
    }
  </script>

</body>
</html>
