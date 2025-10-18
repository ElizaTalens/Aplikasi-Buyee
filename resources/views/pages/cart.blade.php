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

    <div class="mt-6 grid grid-cols-12 gap-8" data-cart id="cartRoot">
      {{-- KIRI: Daftar Item --}}
      <section class="col-span-12 lg:col-span-8">
        <div class="rounded-3xl bg-white ring-1 ring-gray-200">
            
            {{-- TOMBOL CEKLIS SEMUA BARU --}}
            @if(count($cartItems) > 0)
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm font-semibold">
                    <input type="checkbox" id="check-all-items" checked class="h-4 w-4 rounded border-gray-300 text-pink-600 focus:ring-pink-500/70">
                    <span id="check-all-label">Pilih Semua Item ({{ count($cartItems) }} Item)</span>
                </label>
                {{-- Opsi hapus semua item yang dipilih --}}
                <button type="button" data-action="clear-selected" class="text-xs text-rose-600 hover:underline disabled:text-gray-400">
                    Hapus Item Terpilih
                </button>
            </div>
            @endif
            
          <ul id="cart-items-list" class="divide-y divide-gray-200">
            {{-- Loop dinamis untuk setiap item di keranjang --}}
            @forelse ($cartItems as $item)
              <li class="flex items-center gap-6 p-6" data-row data-price="{{ $item->product->price }}" data-id="{{ $item->id }}">
                
                {{-- CHECKBOX ITEM BARU --}}
                <input type="checkbox" checked class="item-checkbox h-5 w-5 rounded border-gray-300 text-pink-600 focus:ring-pink-500/70 shrink-0 self-center">

                <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
                  <img src="{{ isset($item->product->images[0]) ? asset('storage/' . $item->product->images[0]) : asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                </div>

                <div class="min-w-0 flex-1">
                  <h3 class="text-xl font-extrabold">{{ $item->product->name }}</h3>
                  <div class="mt-1 text-sm text-gray-500">Rp{{ number_format($item->product->price) }} per item</div>
                  <div class="mt-2 text-2xl font-extrabold price" data-subtotal>Rp{{ number_format($item->product->price * $item->quantity) }}</div>
                </div>

                <div class="flex items-center gap-4">
                  <button type="button" class="text-rose-600 hover:text-rose-700" data-remove="{{ $item->id }}" title="Remove">
                    <i class="fa-solid fa-trash-can fa-lg"></i>
                  </button>
                  <div class="flex h-11 items-center overflow-hidden rounded-full bg-gray-100 px-2">
                    <button type="button" class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-minus="{{ $item->id }}">
                      <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="mx-3 w-6 text-center font-semibold" data-qty data-max-stock="{{ $item->product->stock_quantity ?? 99 }}">{{ $item->quantity }}</span>
                    <button type="button" class="grid h-8 w-8 place-items-center rounded-full hover:bg-gray-200" data-plus="{{ $item->id }}">
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
              <dt class="text-gray-600">Total Item Dipilih</dt>
              <dd id="total" class="text-2xl font-bold">Rp0</dd>
            </div>
          </dl>
          
          {{-- KOREKSI: Tombol Checkout memanggil fungsi JS --}}
          <button type="button" data-action="checkout"
            id="checkout-button"
            class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-900 disabled:opacity-50 disabled:cursor-not-allowed">
            Go to Checkout
            <i class="fa-solid fa-arrow-right-long ml-3"></i>
          </button>
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

    // FUNGSI UTAMA: MENGHITUNG ULANG TOTAL
    function recalculateTotals() {
        const rows = document.querySelectorAll('[data-row]');
        let total = 0;
        let selectedCount = 0;
        
        rows.forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            const price = Number(row.dataset.price);
            const qty = Number(row.querySelector('[data-qty]').textContent);
            const subtotal = price * qty;
            
            // Mengubah tampilan subtotal per item
            row.querySelector('[data-subtotal]').textContent = formatCurrency(subtotal);
            
            if (checkbox && checkbox.checked) {
                total += subtotal;
                selectedCount++;
            }
        });
        
        document.getElementById('total').textContent = formatCurrency(total);

        // Update UI untuk tombol checkout dan label
        const rowsCount = rows.length;
        const isAnyItemSelected = selectedCount > 0;
        const checkoutButton = document.getElementById('checkout-button');
        const isCheckoutDisabled = !isAnyItemSelected;

        checkoutButton.disabled = isCheckoutDisabled;
        checkoutButton.classList.toggle('disabled:bg-gray-400', isCheckoutDisabled);
        checkoutButton.classList.toggle('disabled:cursor-not-allowed', isCheckoutDisabled);
        
        // Update teks 'Pilih Semua Item'
        const checkAllLabelEl = document.getElementById('check-all-label');
        if (checkAllLabelEl) {
            checkAllLabelEl.textContent = `Pilih Semua Item (${selectedCount} Item Dipilih)`;
        }
        
        // Sinkronisasi checkbox 'Pilih Semua'
        const checkAllCheckbox = document.getElementById('check-all-items');
        if (checkAllCheckbox) {
            checkAllCheckbox.checked = selectedCount === rowsCount && rowsCount > 0;
        }
    }


    // FUNGSI API BARU: UPDATE KUANTITAS DENGAN METHOD OVERRIDE
    async function updateQuantity(cartId, newQuantity) {
        // Data dikirim sebagai form URL-encoded dengan method override
        const data = { 
            quantity: newQuantity,
            _method: 'PUT', // PENTING: Untuk override method PUT
            _token: csrfToken 
        };

        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'POST', // Kirim sebagai POST
                headers: { 
                    'X-CSRF-TOKEN': csrfToken, 
                    'Accept': 'application/json' 
                },
                body: new URLSearchParams(data) // Kirim data sebagai URL-encoded (paling kompatibel)
            });
            
            if (!response.ok) {
                 const errorJson = await response.json().catch(() => ({ message: 'Gagal membaca server response.' }));
                 const errorMsg = (errorJson.errors && Object.values(errorJson.errors)[0][0]) || errorJson.message || 'Gagal mengubah kuantitas.';
                 alert(`Error: ${errorMsg}`);
                 return false;
            }

            return true;
        } catch (error) {
            console.error('Error updating quantity:', error);
            alert('Terjadi kesalahan koneksi.');
            return false;
        }
    }

    // FUNGSI UTAMA KUANTITAS: Mengatur Logika +/-, memanggil API
    async function changeQty(button, amount) {
        const row = button.closest('[data-row]');
        const qtyEl = row.querySelector('[data-qty]');
        const cartId = row.dataset.id; // Menggunakan data-id
        const maxStock = Number(qtyEl.dataset.maxStock || 99); 
        let currentQty = Number(qtyEl.textContent);
        const newQty = currentQty + amount;

        // Validasi Kuantitas
        if (newQty < 1) {
             if (confirm('Kuantitas mencapai nol. Hapus item ini dari keranjang?')) {
                 const removeBtn = row.querySelector('[data-remove]');
                 if(removeBtn) removeItem(removeBtn);
             }
             return; 
        }
        if (newQty > maxStock) {
             alert(`Stok maksimal produk ini hanya ${maxStock}.`);
             return;
        }

        row.querySelectorAll('button').forEach(btn => btn.disabled = true);
        
        const success = await updateQuantity(cartId, newQty);
        
        row.querySelectorAll('button').forEach(btn => btn.disabled = false);

        if (success) {
            qtyEl.textContent = newQty; // Update UI jika sukses
            recalculateTotals(); // Hitung ulang total
            if (typeof updateCartCountFromDB === 'function') updateCartCountFromDB();
        } 
    }
    
    // Pastikan removeItem memanggil recalculateTotals setelah menghapus baris
    async function removeItem(button) {
        if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
            return;
        }

        const row = button.closest('[data-row]');
        const cartId = row.dataset.id; // Menggunakan data-id
        button.disabled = true;

        try {
            const response = await fetch(`/cart/${cartId}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            });

            if (response.ok) {
                row.remove();
                recalculateTotals(); // PENTING: Hitung ulang setelah remove
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

    // FUNGSI BARU: HAPUS ITEM TERPILIH (clearSelectedItems)
    async function clearSelectedItems() {
        const selectedIds = [];
        document.querySelectorAll('[data-row]').forEach(row => {
            if (row.querySelector('.item-checkbox').checked) {
                selectedIds.push(row.dataset.id); // Menggunakan data-id
            }
        });

        if (selectedIds.length === 0) {
            alert('Tidak ada item yang dipilih untuk dihapus.');
            return;
        }

        if (!confirm(`Yakin ingin menghapus ${selectedIds.length} item dari keranjang?`)) {
            return;
        }

        // KOREKSI: Menggunakan method POST + _method=DELETE untuk penghapusan massal
        const deleteData = {
            ids: selectedIds,
            _method: 'DELETE', 
            _token: csrfToken
        };

        try {
            const response = await fetch(`/cart/delete-multiple`, { 
                method: 'POST', // Kirim sebagai POST
                headers: { 
                    'Content-Type': 'application/json', // Kirim sebagai JSON
                    'X-CSRF-TOKEN': csrfToken 
                },
                body: JSON.stringify(deleteData) 
            });

            if (response.ok) {
                alert('Item berhasil dihapus!');
                // Hapus baris yang sesuai dari DOM
                selectedIds.forEach(id => {
                    const rowToRemove = document.querySelector(`[data-id="${id}"]`);
                    if(rowToRemove) rowToRemove.remove();
                });
                recalculateTotals(); 
                if (typeof updateCartCountFromDB === 'function') updateCartCountFromDB();
            } else {
                 const result = await response.json().catch(() => ({ message: 'Kesalahan server.' }));
                 alert(result.message || 'Gagal menghapus item.');
            }
        } catch (error) {
            alert('Terjadi kesalahan koneksi.');
        }
    }

    // FUNGSI UTAMA: MENGARAHKAN KE CHECKOUT
    function goToCheckout() {
        const selectedIds = [];
        document.querySelectorAll('[data-row]').forEach(row => {
            const checkbox = row.querySelector('.item-checkbox');
            if (checkbox.checked) {
                selectedIds.push(row.dataset.id);
            }
        });

        if (selectedIds.length === 0) {
            alert('Mohon pilih setidaknya satu item untuk melanjutkan ke Checkout.');
            return;
        }

        const checkoutUrl = '{{ route('checkout') }}' + '?items=' + selectedIds.join(',');
        window.location.href = checkoutUrl;
    }

    // --- EVENT ATTACHMENT ---
    document.addEventListener('DOMContentLoaded', () => {
        recalculateTotals(); 
        
        // Attachment event listeners ke Tombol +/-
        document.querySelectorAll('[data-plus]').forEach(btn => {
            btn.addEventListener('click', () => changeQty(btn, 1));
        });

        document.querySelectorAll('[data-minus]').forEach(btn => {
            btn.addEventListener('click', () => changeQty(btn, -1));
        });

        // Event listener untuk tombol Hapus di setiap baris
        document.querySelectorAll('[data-remove]').forEach(btn => {
            btn.addEventListener('click', () => removeItem(btn));
        });

        // Event listener untuk tombol Hapus Item Terpilih
        document.querySelector('button[data-action="clear-selected"]').addEventListener('click', clearSelectedItems);

        // Event listener untuk checkbox "Pilih Semua"
        const checkAllCheckbox = document.getElementById('check-all-items');
        if (checkAllCheckbox) {
            checkAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                    checkbox.checked = isChecked;
                });
                recalculateTotals();
            });
        }
        
        // Event listener untuk checkbox per item
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', recalculateTotals);
        });

        // Event listener untuk tombol Checkout
        document.getElementById('checkout-button').addEventListener('click', goToCheckout);
    });
</script>
@endpush