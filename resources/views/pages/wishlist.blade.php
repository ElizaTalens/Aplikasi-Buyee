<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Your Wishlist — Buyee</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    {{-- WAJIB: Meta tag CSRF token untuk AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-[#f7f7f7] text-gray-900 font-[Inter] antialiased pt-20"> {{-- pt-20 untuk mengimbangi navbar fixed --}}

    @include('layouts.navbar')

    <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-20 pt-6"> {{-- Tambah pt-6 agar konten tidak tertutup navbar --}}
        {{-- Breadcrumbs --}}
        <nav class="pt-30 text-sm text-gray-500">
            <ol class="flex items-center gap-3">
                <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
                <li class="text-gray-300">›</li>
                <li class="text-gray-900">Wishlist</li>
            </ol>
        </nav>

        <h1 class="mt-3 text-[44px] font-extrabold tracking-tight">Your Wishlist</h1>

        <div class="mt-6 grid grid-cols-12 gap-8" data-wishlist id="wishlistRoot">
            {{-- LEFT: items --}}
            <section class="col-span-12 lg:col-span-8">
                <div class="rounded-3xl bg-white ring-1 ring-gray-200 overflow-hidden">

                    {{-- START: LOOP ITEM WISHLIST DINAMIS --}}
                    @forelse ($wishlistItems as $item)
                        @php
                            $product = $item->product;
                            $imgUrl = $product->image ? '/' . $product->image : asset('images/placeholder.jpg'); 
                        @endphp
                        
                        <div class="flex items-center gap-6 p-6" data-item data-id="{{ $item->id }}" data-product-id="{{ $product->id }}">
                            <div class="h-28 w-28 shrink-0 overflow-hidden rounded-xl ring-1 ring-gray-200 bg-gray-50 grid place-items-center">
                                <img src="{{ $imgUrl }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                            </div>

                            <div class="min-w-0 flex-1">
                                <h3 class="text-xl font-extrabold">{{ $product->name }}</h3>
                                <div class="mt-1 text-sm text-gray-600 space-y-0.5">
                                    <div>Kategori: <span class="font-medium">{{ $product->category->name ?? 'N/A' }}</span></div>
                                    {{-- Hilangkan Size/Color karena data produk Anda tidak memilikinya (berdasarkan DB) --}}
                                </div>
                                {{-- KOREKSI HARGA: Gunakan Rupiah dan format angka --}}
                                <div class="mt-3 text-2xl font-extrabold price">Rp{{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-3">
                                {{-- Tombol Hapus Item --}}
                                <button class="text-rose-600 hover:text-rose-700 p-2" data-remove="{{ $item->id }}" title="Remove">
                                    <i class="fa-solid fa-trash-can fa-lg"></i>
                                </button>
                                
                                {{-- Tombol Pindah ke Keranjang --}}
                                <button class="flex items-center rounded-full bg-black px-4 py-2 text-white font-semibold hover:bg-gray-700 text-sm transition" data-move-to-cart="{{ $product->id }}">
                                    Pindahkan ke Keranjang
                                </button>
                            </div>
                        </div>

                        @if (!$loop->last)
                            <hr class="border-gray-200">
                        @endif
                        
                    @empty
                        {{-- State Wishlist Kosong --}}
                        <div class="p-12 text-center text-gray-500" data-empty-state>
                            <i class="fa-regular fa-heart fa-3x mb-4"></i>
                            <p class="text-xl font-semibold">Wishlist kamu masih kosong.</p>
                            <p class="text-sm mt-2">Tambahkan produk yang kamu sukai dari halaman katalog.</p>
                        </div>
                    @endforelse
                    {{-- END: LOOP ITEM WISHLIST DINAMIS --}}
                </div>
            </section>

            {{-- RIGHT: Action Summary --}}
            <aside class="col-span-12 lg:col-span-4">
                <div class="rounded-3xl bg-white ring-1 ring-gray-200 p-6 sticky top-24"> {{-- top-24 untuk mengakomodasi navbar fixed --}}
                    <h3 class="text-2xl font-extrabold text-gray-800">Wishlist Actions</h3>

                    <div class="mt-6 text-lg font-semibold text-gray-700">
                        Total <span id="itemCount">{{ $wishlistItems->count() }}</span> item di wishlist
                    </div>

                    {{-- KOREKSI WARNA: Menggunakan bg-black & text-white --}}
                    <button id="moveAllBtn"
                        class="mt-6 inline-flex w-full items-center justify-center rounded-full bg-black px-6 py-4 text-white font-semibold hover:bg-gray-700 transition disabled:opacity-50 disabled:cursor-not-allowed" data-move-all-to-cart>
                        Pindahkan Semua ke Keranjang
                    </button>
                    
                    <button id="clearAllBtn"
                        class="mt-4 inline-flex w-full items-center justify-center rounded-full border border-gray-300 px-6 py-4 text-gray-700 font-semibold hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed" data-clear-wishlist>
                        Hapus Semua Item
                    </button>
                </div>
            </aside>
        </div>
    </main>

    @include('layouts.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const wishlistRoot = document.getElementById('wishlistRoot');
        const itemCountEl = document.getElementById('itemCount');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const moveAllBtn = document.getElementById('moveAllBtn');
        const clearAllBtn = document.getElementById('clearAllBtn');
        const emptyState = document.querySelector('[data-empty-state]');

        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
        };

        function updateWishlistCount(count) {
            itemCountEl.textContent = count;
            const items = wishlistRoot.querySelectorAll('[data-item]');
            const isVisible = items.length > 0;
            
            // Toggle empty state
            emptyState.classList.toggle('hidden', isVisible); 
            
            // Disable buttons if list is empty
            moveAllBtn.disabled = !isVisible;
            clearAllBtn.disabled = !isVisible;
            moveAllBtn.classList.toggle('opacity-50', !isVisible);
            clearAllBtn.classList.toggle('opacity-50', !isVisible);
        }

        // --- FUNGSI UTILITY AJAX ---

        async function sendAjax(endpoint, method = 'POST', data = {}) {
            try {
                const response = await fetch(endpoint, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: method !== 'GET' ? JSON.stringify(data) : null
                });

                if (response.status === 401) {
                    alert('Sesi habis. Silakan login kembali.');
                    window.location.href = '{{ route("login.form") }}';
                    return null;
                }

                if (!response.ok) {
                    const error = await response.json();
                    throw new Error(error.message || 'Error saat memproses permintaan.');
                }
                
                return response.json();

            } catch (error) {
                alert(`Gagal memproses aksi: ${error.message}`);
                console.error('AJAX Error:', error);
                return null;
            }
        }

        // --- LISTENER UTAMA ---

        wishlistRoot.addEventListener('click', async (e) => {
            const itemElement = e.target.closest('[data-item]');
            
            // Aksi Hapus Item Tunggal
            if (e.target.closest('[data-remove]')) {
                const itemId = e.target.closest('[data-remove]').dataset.remove;
                
                if (confirm('Yakin ingin menghapus item ini dari Wishlist?')) {
                    // Endpoint untuk menghapus item wishlist tunggal (Gunakan rute destroy/delete jika ada)
                    // Karena di routes hanya ada POST store, kita asumsikan model Wishlist memiliki soft delete
                    // Namun karena belum ada rute DELETE/PATCH, kita gunakan POST ke rute store (toggle)
                    
                    const result = await sendAjax('{{ route("wishlist.store") }}', 'POST', { product_id: itemElement.dataset.productId });

                    if (result && result.action === 'removed') {
                        itemElement.nextElementSibling?.remove(); // Hapus <hr>
                        itemElement.remove(); // Hapus item
                        updateWishlistCount(result.count);
                        alert(result.message);
                    }
                }
            } 
            
            // Aksi Pindahkan ke Keranjang Tunggal
            if (e.target.closest('[data-move-to-cart]')) {
                const productId = e.target.closest('[data-move-to-cart]').dataset.moveToCart;
                
                // Panggil endpoint cart store
                const cartResult = await sendAjax('{{ route("cart.store") }}', 'POST', { product_id: productId, quantity: 1 });

                if (cartResult) {
                    // Setelah sukses pindah ke keranjang, hapus dari wishlist (panggil wishlist store lagi)
                    const wishlistResult = await sendAjax('{{ route("wishlist.store") }}', 'POST', { product_id: productId });

                    if (wishlistResult) {
                        itemElement.nextElementSibling?.remove(); // Hapus <hr>
                        itemElement.remove(); // Hapus item
                        updateWishlistCount(wishlistResult.count);
                        alert(`Produk berhasil dipindahkan ke keranjang!`);
                    }
                }
            }
        });
        
        // --- AKSI Pindahkan Semua / Hapus Semua ---

        // Aksi Hapus Semua Item
        clearAllBtn.addEventListener('click', () => {
            if (confirm('Anda yakin ingin menghapus SEMUA item dari Wishlist?')) {
                const allItems = wishlistRoot.querySelectorAll('[data-item]');
                let successfulDeletes = 0;
                
                // Lakukan loop dan panggil AJAX untuk setiap item (atau buat rute massal)
                allItems.forEach(async (item, index) => {
                    const productId = item.dataset.productId;
                    const result = await sendAjax('{{ route("wishlist.store") }}', 'POST', { product_id: productId });
                    
                    if (result && result.action === 'removed') {
                        item.remove();
                        // Hapus HR juga
                        const hr = document.querySelector('.border-gray-200');
                        if(hr) hr.remove();
                        successfulDeletes++;
                        
                        if (index === allItems.length - 1) {
                            updateWishlistCount(0); // Set count to 0 manually
                            alert(`Berhasil menghapus ${successfulDeletes} item dari wishlist.`);
                        }
                    }
                });

                if (allItems.length === 0) {
                     alert('Wishlist sudah kosong.');
                }
            }
        });

        // Aksi Pindahkan Semua ke Keranjang
        moveAllBtn.addEventListener('click', () => {
            if (confirm('Anda yakin ingin memindahkan SEMUA item dari Wishlist ke Keranjang?')) {
                const allItems = wishlistRoot.querySelectorAll('[data-item]');
                let successfulMoves = 0;

                allItems.forEach(async (item, index) => {
                    const productId = item.dataset.productId;
                    
                    // 1. Pindahkan ke Keranjang
                    const cartResult = await sendAjax('{{ route("cart.store") }}', 'POST', { product_id: productId, quantity: 1 });
                    
                    if (cartResult) {
                        // 2. Hapus dari Wishlist
                        const wishlistResult = await sendAjax('{{ route("wishlist.store") }}', 'POST', { product_id: productId });
                        
                        if (wishlistResult) {
                            item.remove();
                            // Hapus HR
                            const hr = document.querySelector('.border-gray-200');
                            if(hr) hr.remove();
                            successfulMoves++;
                            
                            if (index === allItems.length - 1) {
                                updateWishlistCount(0); // Set count to 0 manually
                                alert(`Berhasil memindahkan ${successfulMoves} item ke keranjang.`);
                            }
                        }
                    }
                });

                if (allItems.length === 0) {
                    alert('Wishlist sudah kosong.');
                }
            }
        });

        // Inisialisasi tampilan awal
        updateWishlistCount({{ $wishlistItems->count() }});
    });
</script>
</body>
</html>