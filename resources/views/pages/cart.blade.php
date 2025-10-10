@extends('layouts.master')

@section('title', 'Your Cart — Buyee')

@section('content')
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
    {{-- Breadcrumbs --}}
    <nav class="text-sm text-gray-500">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Cart</li>
      </ol>
    </nav>

    <h1 class="mt-3 text-[44px] font-extrabold tracking-tight">Your Cart</h1>

    <div class="mt-6 grid grid-cols-12 gap-8" data-cart>
      {{-- KIRI: Daftar Item --}}
      <section class="col-span-12 lg:col-span-8">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 overflow-hidden">
          
          {{-- Loop dinamis untuk setiap item di keranjang --}}
          @forelse ($cartItems as $item)
            <div class="flex items-center gap-6 p-6" data-row data-price="{{ $item->product->price }}">
              <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
                <img src="{{ $item->product->images[0] ?? asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
              </div>

              <div class="min-w-0 flex-1">
                <h3 class="text-xl font-extrabold">{{ $item->product->name }}</h3>
                <div class="mt-1 text-sm text-gray-600 space-y-0.5">
                  <div>Size: <span class="font-medium">Large</span></div> {{-- Ganti dengan data varian jika ada --}}
                  <div>Color: <span class="font-medium">White</span></div> {{-- Ganti dengan data varian jika ada --}}
                </div>
                <div class="mt-3 text-2xl font-extrabold price">Rp{{ number_format($item->product->price) }}</div>
              </div>

              <div class="flex items-center gap-4">
                <button class="text-rose-600 hover:text-rose-700" data-remove title="Remove">
                  <i class="fa-solid fa-trash-can"></i>
                </button>

                <div class="flex h-11 items-center overflow-hidden rounded-full bg-gray-100 px-2">
                  <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-minus>
                    <i class="fa-solid fa-minus"></i>
                  </button>
                  <span class="mx-3 w-6 text-center font-semibold" data-qty>{{ $item->quantity }}</span>
                  <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-plus>
                    <i class="fa-solid fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
            @if (!$loop->last)
              <hr class="border-gray-200">
            @endif
          @empty
            <div class="p-12 text-center text-gray-500">
              <i class="fa-solid fa-cart-shopping fa-3x mb-4"></i>
              <p class="text-xl font-semibold">Keranjang belanja Anda kosong.</p>
              <a href="{{ route('catalog') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Mulai Belanja</a>
            </div>
          @endforelse
          
        </div>
      </section>

      {{-- KANAN: Ringkasan Pesanan --}}
      <aside class="col-span-12 lg:col-span-4">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-6">
          <h3 class="text-2xl font-extrabold">Order Summary</h3>

          <dl class="mt-6 space-y-4">
            <div class="flex items-center justify-between">
              <dt class="text-gray-600">Subtotal</dt>
              <dd id="subtotal" class="text-lg font-semibold">Rp0</dd>
            </div>
            <div class="flex items-center justify-between">
              <dt class="text-gray-600">Delivery Fee</dt>
              <dd id="delivery" class="text-lg font-semibold">Rp0</dd>
            </div>
            <hr class="border-gray-200">
            <div class="flex items-center justify-between">
              <dt class="text-gray-900 font-semibold">Total</dt>
              <dd id="grand" class="text-2xl font-extrabold">Rp0</dd>
            </div>
          </dl>

          {{-- FIX: Tombol diubah menjadi link ke route checkout --}}
          <a href="{{ route('checkout') }}"
            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-900">
            Go to Checkout
            <i class="fa-solid fa-arrow-right-long ml-3"></i>
          </a>
        </div>
      </aside>
    </div>
  </main>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartRoot = document.querySelector('[data-cart]');
        if (!cartRoot) return;

        // Fungsi untuk format mata uang
        const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        const formatCurrency = n => formatter.format(n).replace('Rp', 'Rp');

        // Fungsi untuk menghitung ulang total
        function recalculateTotals() {
            let subtotal = 0;
            cartRoot.querySelectorAll('[data-row]').forEach(row => {
                const price = Number(row.dataset.price || 0);
                const qty = Number(row.querySelector('[data-qty]')?.textContent || 0);
                subtotal += price * qty;
            });
            
            const deliveryFee = subtotal > 0 ? 15000 : 0; // Contoh ongkos kirim
            const grandTotal = subtotal + deliveryFee;

            document.getElementById('subtotal').textContent = formatCurrency(subtotal);
            document.getElementById('delivery').textContent = formatCurrency(deliveryFee);
            document.getElementById('grand').textContent = formatCurrency(grandTotal);

            // Sembunyikan checkout jika keranjang kosong
            const checkoutButton = cartRoot.querySelector('a[href*="checkout"]');
            if(checkoutButton) checkoutButton.style.display = subtotal > 0 ? 'inline-flex' : 'none';
        }

        // Tambahkan event listener untuk tombol
        cartRoot.addEventListener('click', (e) => {
            const row = e.target.closest('[data-row]');
            if (!row) return;
            
            const qtyEl = row.querySelector('[data-qty]');
            let qty = Number(qtyEl.textContent);

            // Tombol Plus
            if (e.target.closest('[data-plus]')) {
                qtyEl.textContent = qty + 1;
                recalculateTotals();
            }
            
            // Tombol Minus
            if (e.target.closest('[data-minus]')) {
                if (qty > 1) {
                    qtyEl.textContent = qty - 1;
                    recalculateTotals();
                }
            }

            // Tombol Hapus
            if (e.target.closest('[data-remove]')) {
                row.remove();
                // Hapus juga tag <hr> di atasnya jika ada
                const hr = row.previousElementSibling;
                if(hr && hr.tagName === 'HR') hr.remove();
                recalculateTotals();
            }
        });

        // Hitung total saat halaman pertama kali dimuat
        recalculateTotals();
    });
</script>
@endpush