<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your Cart — Buyee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- WAJIB: Meta tag CSRF token untuk AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased"> {{-- Tambahkan pt-20 untuk navbar fixed --}}

    @include('layouts.navbar')

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20">
        {{-- Breadcrumbs --}}
        <nav class="pt-30 text-sm text-gray-500">
            <ol class="flex items-center gap-3">
                <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
                <li class="text-gray-300">›</li>
                <li class="text-gray-900">Cart</li>
            </ol>
        </nav>

        <h1 class="mt-3 text-[44px] font-extrabold tracking-tight">Your Cart</h1>

        {{-- KOREKSI: Gunakan Grid untuk menempatkan Summary di Kanan --}}
        <div class="mt-6 grid grid-cols-12 gap-8" data-cart id="cartRoot"> 
            
            {{-- KIRI: Daftar Item --}}
            <section class="col-span-12 lg:col-span-8">
                <div class="rounded-3xl bg-white ring-1 ring-gray-200 overflow-hidden">
                    
                    {{-- START: LOOP ITEM KERANJANG DINAMIS --}}
                    @forelse ($cartItems as $item)
                        <div class="flex items-center gap-6 p-6" 
                            data-row 
                            data-id="{{ $item->id }}"
                            data-price="{{ $item->product->price }}">

                            {{-- START: CHECKBOX DINAMIS --}}
                            <input type="checkbox" data-checkbox="{{ $item->id }}" checked 
                                class="h-5 w-5 shrink-0 rounded border-gray-300 text-black focus:ring-black">
                            {{-- END: CHECKBOX DINAMIS --}}
                            
                            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
                                @php
                                    $imgUrl = $item->product->image ? '/' . $item->product->image : asset('images/placeholder.jpg'); 
                                @endphp
                                <img src="{{ $imgUrl }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-xl font-extrabold">{{ $item->product->name }}</h3>
                                <div class="mt-1 text-sm text-gray-600 space-y-0.5">
                                    <div>Category: <span class="font-medium">{{ $item->product->category->name ?? 'N/A' }}</span></div> 
                                </div>
                                {{-- Harga Satuan Dinamis --}}
                                <div class="mt-3 text-2xl font-extrabold price">Rp{{ number_format($item->product->price, 0, ',', '.') }}</div>
                            </div>

                            <div class="flex items-center gap-4">
                                {{-- Tombol Hapus --}}
                                <button class="text-rose-600 hover:text-rose-700" data-remove="{{ $item->id }}" title="Remove">
                                    <i class="fa-solid fa-trash-can"></i>
                                </button>

                                {{-- Kontrol Kuantitas --}}
                                <div class="flex h-11 items-center overflow-hidden rounded-full border border-gray-300">
                                    <button class="grid h-8 w-8 place-items-center hover:bg-gray-100 disabled:opacity-50" data-minus="{{ $item->id }}">
                                        <i class="fa-solid fa-minus"></i>
                                    </button>
                                    <span class="mx-3 w-6 text-center font-semibold" data-qty data-max-stock="{{ $item->product->stock }}">{{ $item->quantity }}</span>
                                    <button class="grid h-8 w-8 place-items-center hover:bg-gray-100 disabled:opacity-50" data-plus="{{ $item->id }}">
                                        <i class="fa-solid fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @if (!$loop->last)
                            <hr class="border-gray-200">
                        @endif
                    @empty
                        {{-- State Keranjang Kosong --}}
                        <div class="p-12 text-center text-gray-500" data-empty-state id="emptyState">
                            <i class="fa-solid fa-cart-shopping fa-3x mb-4"></i>
                            <p class="text-xl font-semibold">Keranjang belanja Anda kosong.</p>
                            <a href="{{ route('catalog') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Mulai Belanja</a>
                        </div>
                    @endforelse
                    {{-- END: LOOP ITEM KERANJANG DINAMIS --}}
                </div>
            </section>

            
            {{-- RIGHT: summary (Menggunakan sticky agar mengikuti scroll) --}}
            <aside class="col-span-12 lg:col-span-4">
                <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-24"> {{-- Ubah top-6 menjadi top-24 untuk mengakomodasi navbar fixed --}}
                    <h3 class="text-2xl font-extrabold">Order Summary</h3>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">Subtotal (Item yang dipilih)</dt>
                            <dd id="subtotal" class="text-lg font-semibold">Rp 0</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-600">Delivery Fee</dt>
                            <dd id="delivery" class="text-lg font-semibold">Rp 0</dd>
                        </div>
                        <hr class="border-gray-200">
                        <div class="flex items-center justify-between">
                            <dt class="text-gray-900 font-semibold">Total</dt>
                            <dd id="grand" class="text-2xl font-extrabold">Rp 0</dd>
                        </div>
                    </dl>
                    
                    {{-- KOREKSI: Tombol Checkout ke Halaman Checkout --}}
                    <a href="{{ route('checkout') }}" id="checkoutButton"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-900 disabled:opacity-50 disabled:cursor-not-allowed">
                        Go to Checkout
                        <i class="fa-solid fa-arrow-right-long ml-3"></i>
                    </a>
                    
                </div>
            </aside>
        </div>
    </main>

    @include('layouts.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cartRoot = document.querySelector('[data-cart]');
        if (!cartRoot) return;

        // Helper functions
        const formatter = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
        const formatCurrency = n => formatter.format(n).replace('Rp', 'Rp');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Mendapatkan referensi elemen-elemen summary
        const subtotalEl = document.getElementById('subtotal');
        const deliveryEl = document.getElementById('delivery');
        const grandEl = document.getElementById('grand');
        // KOREKSI: Dapatkan elemen <a> yang merupakan tombol checkout
        const checkoutBtn = document.getElementById('checkoutButton'); 
        const emptyState = cartRoot.querySelector('[data-empty-state]');

        // Langkah 2.2: FUNGSI UNTUK MENGHITUNG ULANG TOTAL (Hanya Hitung yang Diceklis)
        function recalculateTotals() {
            let subtotal = 0;
            const rows = cartRoot.querySelectorAll('[data-row]');
            let isAnyItemSelected = false; // Flag baru

            // Loop melalui setiap item keranjang yang tersisa di DOM
            rows.forEach(row => {
                const checkbox = row.querySelector('[data-checkbox]');
                const qtyEl = row.querySelector('[data-qty]');

                // HANYA hitung jika checkbox Dicentang
                if (checkbox && checkbox.checked) {
                    const price = Number(row.dataset.price || 0);
                    const qty = Number(qtyEl?.textContent || 0);
                    subtotal += price * qty;
                    isAnyItemSelected = true;
                }
            });
            
            const deliveryFee = isAnyItemSelected ? 15000 : 0; // Ongkos kirim hanya jika ada item dipilih
            const grandTotal = subtotal + deliveryFee;
            
            // Update display
            subtotalEl.textContent = formatCurrency(subtotal);
            deliveryEl.textContent = formatCurrency(deliveryFee);
            grandEl.textContent = formatCurrency(grandTotal);

            // KOREKSI: Mengelola status tombol Checkout
            const isCartEmpty = rows.length === 0;
            
            // Tampilkan/Sembunyikan tombol checkout & empty state
            if (isCartEmpty) {
                checkoutBtn.style.display = 'none';
                if (emptyState) emptyState.style.display = 'block';
            } else {
                checkoutBtn.style.display = 'inline-flex';
                // Jika keranjang tidak kosong, nonaktifkan tombol jika tidak ada yang diceklis
                checkoutBtn.disabled = !isAnyItemSelected;
                checkoutBtn.style.opacity = isAnyItemSelected ? 1 : 0.6;
                checkoutBtn.style.pointerEvents = isAnyItemSelected ? 'auto' : 'none'; // Menonaktifkan klik
                if (emptyState) emptyState.style.display = 'none';
            }
        }

        // Fungsi untuk mengirim permintaan AJAX ke backend (DELETE/UPDATE)
        // Dibiarkan sebagai demo/placeholder, seperti pada kode asli Anda
        async function updateCartItem(action, itemId, quantity = null) {
            // ... (AJAX logic)
            return true;
        }

        // --- Event Listener untuk Checkbox ---
        cartRoot.addEventListener('change', (e) => {
            if (e.target.closest('[data-checkbox]')) {
                recalculateTotals();
            }
        });

        // --- Event Listener untuk Perubahan Item (Plus/Minus/Remove) ---
        cartRoot.addEventListener('click', async (e) => {
            const row = e.target.closest('[data-row]');
            if (!row) return;
            
            const qtyEl = row.querySelector('[data-qty]');
            let qty = Number(qtyEl?.textContent || 0);
            const itemId = row.dataset.id;
            const maxStock = Number(qtyEl?.dataset.maxStock || 0);
            let actionType = null;
            let shouldUpdate = false;

            if (e.target.closest('[data-plus]')) {
                if (qty < maxStock) {
                    qtyEl.textContent = qty + 1;
                    shouldUpdate = true;
                    actionType = 'update';
                }
            } else if (e.target.closest('[data-minus]')) {
                if (qty > 1) {
                    qtyEl.textContent = qty - 1;
                    shouldUpdate = true;
                    actionType = 'update';
                } else if (qty === 1) {
                    if (confirm('Kuantitas mencapai nol. Hapus item ini dari keranjang?')) {
                        actionType = 'remove';
                    }
                }
            } else if (e.target.closest('[data-remove]')) {
                actionType = 'remove';
            }
            
            // Logika Eksekusi
            if (actionType === 'update') {
                // Simulasi update
                recalculateTotals(); 
            } else if (actionType === 'remove') {
                // Simulasi remove
                const checkbox = row.querySelector('[data-checkbox]');
                const isChecked = checkbox ? checkbox.checked : false;
                
                row.remove(); 
                const hr = row.previousElementSibling;
                if(hr && hr.tagName === 'HR') hr.remove();
                
                recalculateTotals();
            }
        });

        // Hitung total saat halaman pertama kali dimuat
        recalculateTotals();
    });
</script>
</body>
</html>