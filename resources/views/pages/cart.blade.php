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
        <div class="rounded-3xl bg-white ring-1 ring-gray-200">
          <ul id="cart-items-list" class="divide-y divide-gray-200">
            {{-- Loop dinamis untuk setiap item di keranjang --}}
            @forelse ($cartItems as $item)
              <li class="flex items-center gap-6 p-6" data-row data-price="{{ $item->product->price }}" data-cart-id="{{ $item->id }}">
                <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
                  <img src="{{ isset($item->product->images[0]) ? asset('storage/' . $item->product->images[0]) : asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                </div>

                <div class="min-w-0 flex-1">
                  <h3 class="text-xl font-extrabold">{{ $item->product->name }}</h3>
                  {{-- Anda bisa menambahkan logika untuk menampilkan varian di sini --}}
                  <div class="mt-1 text-sm text-gray-500">Rp{{ number_format($item->product->price) }} per item</div>
                  <div class="mt-2 text-2xl font-extrabold price" data-subtotal>Rp{{ number_format($item->product->price * $item->quantity) }}</div>
                </div>

                <div class="flex items-center gap-4">
                  <button class="text-rose-600 hover:text-rose-700" onclick="removeItem(this)" title="Remove">
                    <i class="fa-solid fa-trash-can fa-lg"></i>
                  </button>
                  <div class="flex h-11 items-center overflow-hidden rounded-full bg-gray-100 px-2">
                    <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" onclick="decreaseQty(this)">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="mx-3 w-6 text-center font-semibold" data-qty>{{ $item->quantity }}</span>
                    <button class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" onclick="increaseQty(this)">
                      <i class="fa-solid fa-plus"></i>
                    </button>
                  </div>
                </div>
              </li>
            @empty
              <div id="empty-cart-message" class="p-12 text-center text-gray-500">
                <i class="fa-solid fa-cart-shopping fa-3x mb-4"></i>
                <p class="text-xl font-semibold">Keranjang belanja Anda kosong.</p>
                <a href="{{ route('catalog') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Mulai Belanja</a>
              </div>
            @endforelse
          </ul>
        </div>
      </section>

      {{-- KANAN: Ringkasan Pesanan --}}
      <aside class="col-span-12 lg:col-span-4">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-6">
          <h3 class="text-2xl font-extrabold">Ringkasan Pesanan</h3>

          <dl class="mt-6 space-y-4">
            <div class="flex items-center justify-between">
              <dt class="text-gray-600">Total</dt>
              <dd id="total" class="text-2xl font-bold">Rp0</dd>
            </div>
          </dl>
          
          <a href="{{ route('checkout') }}"
            id="checkout-button"
            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-900 disabled:bg-gray-400 disabled:cursor-not-allowed">
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    function formatCurrency(amount) {
        return 'Rp' + new Intl.NumberFormat('id-ID').format(amount);
    }

    function recalculateTotals() {
        const rows = document.querySelectorAll('[data-row]');
        let total = 0;
        
        rows.forEach(row => {
            const price = Number(row.dataset.price);
            const qty = Number(row.querySelector('[data-qty]').textContent);
            const subtotal = price * qty;
            row.querySelector('[data-subtotal]').textContent = formatCurrency(subtotal);
            total += subtotal;
        });
        
        document.getElementById('total').textContent = formatCurrency(total);

        // Update UI jika keranjang kosong
        const isEmpty = rows.length === 0;
        const checkoutButton = document.getElementById('checkout-button');
        checkoutButton.classList.toggle('disabled:bg-gray-400', isEmpty);
        checkoutButton.classList.toggle('disabled:cursor-not-allowed', isEmpty);
        if(isEmpty) {
            checkoutButton.setAttribute('disabled', 'disabled');
            checkoutButton.href = '#'; // Cegah klik
        } else {
            checkoutButton.removeAttribute('disabled');
            checkoutButton.href = "{{ route('checkout') }}";
        }
    }

    async function removeItem(button) {
        if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
            return;
        }

        const row = button.closest('[data-row]');
        const cartId = row.dataset.cartId;
        button.disabled = true;

        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });

            if (response.ok) {
                row.remove();
                recalculateTotals();
                if (typeof updateCartCountFromDB === 'function') updateCartCountFromDB();
            } else {
                alert('Gagal menghapus item. Silakan coba lagi.');
            }
        } catch (error) {
            alert('Terjadi kesalahan. Periksa koneksi Anda.');
        } finally {
            button.disabled = false;
        }
    }

    async function updateQuantity(cartId, newQuantity) {
        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'PUT',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ quantity: newQuantity })
            });
            return response.ok;
        } catch (error) {
            console.error('Error updating quantity:', error);
            return false;
        }
    }

    async function changeQty(button, amount) {
        const row = button.closest('[data-row]');
        const qtyEl = row.querySelector('[data-qty]');
        const cartId = row.dataset.cartId;
        let currentQty = Number(qtyEl.textContent);
        const newQty = currentQty + amount;

        if (newQty < 1) return; // Kuantitas tidak boleh kurang dari 1

        button.closest('.flex').querySelectorAll('button').forEach(btn => btn.disabled = true);
        
        const success = await updateQuantity(cartId, newQty);
        if (success) {
            qtyEl.textContent = newQty;
            recalculateTotals();
            if (typeof updateCartCountFromDB === 'function') updateCartCountFromDB();
        } else {
            alert('Gagal mengubah jumlah item.');
        }

        button.closest('.flex').querySelectorAll('button').forEach(btn => btn.disabled = false);
    }
    
    function increaseQty(button) { changeQty(button, 1); }
    function decreaseQty(button) { changeQty(button, -1); }
    
    document.addEventListener('DOMContentLoaded', recalculateTotals);
</script>
@endpush