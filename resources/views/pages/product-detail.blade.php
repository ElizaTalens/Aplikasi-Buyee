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
    
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
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
                            $imgUrl = $product->image ? '/' . $product->image : asset('images/placeholder.jpg'); 
                            $fallback = asset('images/placeholder.jpg'); 
                        @endphp
                        <img
                            src="{{ $imgUrl }}" 
                            alt="{{ $product->name }}"
                            class="max-h-[420px] object-contain"
                            onerror="this.onerror=null;this.src='{{ $fallback }}';"
                            >
                    </div>
                </div>

                {{-- RIGHT: Detail --}}
                <div class="col-span-12 lg:col-span-6">
                    <h1 class="text-[34px] leading-tight font-extrabold">{{ $product->name }}</h1>
                    <div class="mt-3 flex items-center gap-3">
                        <div class="text-3xl font-extrabold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    </div>
                    <p class="mt-4 text-[13px] leading-6 text-gray-600 max-w-prose">
                        {{ $product->description ?? 'Deskripsi produk ini belum tersedia. Silakan hubungi admin untuk informasi lebih lanjut.' }}
                    </p>
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

                    <form id="addToCartForm">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                        {{-- START: ATUR JUMLAH & SUBTOTAL --}}
                        <div class="p-4 rounded-xl border border-gray-200 bg-gray-50 mt-6" 
                            data-product-price="{{ $product->price }}" data-product-stock="{{ $product->stock }}">
                            <h4 class="text-lg font-bold mb-3">Atur jumlah dan catatan</h4>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center overflow-hidden rounded-full border border-gray-300">
                                    <button type="button" class="grid h-8 w-8 place-items-center hover:bg-gray-200 disabled:opacity-50" id="btn-qty-minus">
                                        <i class="fa-solid fa-minus text-sm"></i>
                                    </button>
                                    <input type="number" id="input-qty" value="1" min="1" max="{{ $product->stock }}" name="quantity"
                                            class="w-12 text-center font-semibold border-none bg-transparent focus:ring-0 p-0" step="1">
                                    <button type="button" class="grid h-8 w-8 place-items-center hover:bg-gray-200 disabled:opacity-50" id="btn-qty-plus">
                                        <i class="fa-solid fa-plus text-sm"></i>
                                    </button>
                                </div>
                                
                                <span class="text-sm text-gray-600">Stok Total: Sisa <span class="font-bold text-orange-500">{{ $product->stock }}</span></span>
                            </div>

                            <div class="mt-4 flex items-center justify-between border-t border-gray-300 pt-3">
                                <span class="text-base font-semibold text-gray-700">Subtotal</span>
                                <span class="text-xl font-extrabold" id="subtotal-display">Rp {{ number_format($product->price, 0, ',', '.') }}</span> 
                            </div>
                        </div>
                        {{-- END: ATUR JUMLAH & SUBTOTAL --}}

                        {{-- Actions --}}
                        <div class="mt-8 flex flex-col sm:flex-row gap-4">
                            <button id="btnWishlist" type="button"
                                    data-id="{{ $product->id }}"
                                    class="inline-flex h-11 flex-1 items-center justify-center rounded-md border border-gray-300 bg-white px-6 text-[15px] font-semibold text-gray-900 hover:bg-gray-50">
                                <i class="fa-regular fa-heart mr-2"></i>Add to Wishlist
                            </button>

                            <button id="btnCart" type="submit"
                                    data-id="{{ $product->id }}"
                                    class="inline-flex h-11 flex-1 items-center justify-center rounded-md bg-black px-6 text-[15px] font-semibold text-white hover:bg-gray-900">
                                <i class="fa-solid fa-cart-plus mr-2"></i>Add to Cart
                            </button>
                        </div>
                    </form>
                    </div>
            </div>
        </div>

    </main>
@include('layouts.footer')

<script>
    // Helper function untuk format mata uang
    const formatCurrency = (amount) => {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
    };

    const updateBadgeCount = (type, count) => {
        const countElementId = type === 'cart' ? 'cartCount' : 'wishlistCount';
        const countElement = document.getElementById(countElementId);
        if (countElement) {
            countElement.textContent = count > 0 ? count : '';
            countElement.classList.toggle('hidden', count === 0);
        }
    }
    
    const runButtonAnimation = (button, type, action) => {
        const isCart = type === 'cart';
        let originalIcon = isCart ? 'fa-solid fa-cart-plus' : 'fa-regular fa-heart';
        const originalText = isCart ? 'Add to Cart' : 'Add to Wishlist';
        
        let successText, successClass, originalClass;

        if (isCart) {
            successText = action === 'added' || action === 'updated' ? 'Added!' : 'Error';
            successClass = 'bg-green-600 hover:bg-green-700';
            originalClass = 'bg-black hover:bg-gray-900';
        } else {
            successText = action === 'added' ? 'Added!' : 'Removed!';
            successClass = action === 'added' ? 'border-rose-500 text-rose-500' : 'border-gray-500 text-gray-500';
            originalClass = 'border-gray-300 hover:bg-gray-50 text-gray-900';
        }

        button.disabled = true;
        button.innerHTML = `<i class="fa-solid fa-check mr-2"></i> ${successText}`;
        
        // KOREKSI UTAMA UNTUK MENCEGAH DOMTOKENLIST ERROR
        if (isCart) {
            button.classList.add(...successClass.split(' ')); 
            button.classList.remove(...originalClass.split(' '));
        } else {
            button.classList.remove('text-gray-900', 'border-gray-300', 'hover:bg-gray-50');
            button.classList.add(...successClass.split(' '));
        }

        setTimeout(() => {
            button.innerHTML = `<i class="${originalIcon} mr-2"></i> ${originalText}`;
            if (isCart) {
                button.classList.remove(...successClass.split(' ')); 
                button.classList.add(...originalClass.split(' '));
            } else {
                button.classList.remove(...successClass.split(' '));
                button.classList.add('text-gray-900', 'border-gray-300', 'hover:bg-gray-50');
            }
            button.disabled = false;
        }, 1500);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // --- Setup Variables ---
        const priceElement = document.querySelector('[data-product-price]');
        const stockElement = document.querySelector('[data-product-stock]');
        
        if (!priceElement || !stockElement) return;

        const price = parseFloat(priceElement.dataset.productPrice);
        const stock = parseInt(stockElement.dataset.productStock);
        const qtyInput = document.getElementById('input-qty');
        const minusBtn = document.getElementById('btn-qty-minus');
        const plusBtn = document.getElementById('btn-qty-plus');
        const subtotalDisplay = document.getElementById('subtotal-display');
        const addToCartForm = document.getElementById('addToCartForm');
        const btnWishlist = document.getElementById('btnWishlist');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content; 

        // --- Core Functions (updateSubtotal, updateBadgeCount, runButtonAnimation sama) ---
        
        function updateSubtotal() {
            let qty = parseInt(qtyInput.value);
            
            if (isNaN(qty) || qty < 1) {
                qty = 1;
            } else if (qty > stock) {
                qty = stock; 
            }
            qtyInput.value = qty;
            const subtotal = qty * price;
            subtotalDisplay.textContent = formatCurrency(subtotal);
            minusBtn.disabled = qty <= 1;
            plusBtn.disabled = qty >= stock;
        }

        // --- A. Quantity & Subtotal Handlers ---
        minusBtn.addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) {
                qtyInput.value = parseInt(qtyInput.value) - 1;
                updateSubtotal();
            }
        });

        plusBtn.addEventListener('click', () => {
            if (parseInt(qtyInput.value) < stock) {
                qtyInput.value = parseInt(qtyInput.value) + 1;
                updateSubtotal();
            }
        });

        qtyInput.addEventListener('change', updateSubtotal);


        // --- B. Add to Cart Logic (AJAX SUBMISSION) ---
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // --- KOREKSI UTAMA: Membangun Payload JSON yang Benar ---
                const productId = this.querySelector('input[name="product_id"]').value;
                const quantity = parseInt(document.getElementById('input-qty').value);
                
                const payload = {
                    product_id: productId,
                    quantity: quantity
                };

                fetch('{{ route("cart.store") }}', { 
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest' // <-- Pastikan ini ADA
                    },
                    body: JSON.stringify(payload)
                })
                .then(response => {
                    // Penanganan response 401/redirect ke login
                    if (response.status === 401 || response.url.includes('login')) {
                        alert('Gagal menambahkan ke keranjang: Silakan login terlebih dahulu.');
                        window.location.href = '{{ route("login.form") }}'; 
                        return Promise.reject('Unauthorized'); // Stop further processing
                    }
                    
                    if (!response.ok) {
                        // Ini menangkap error dari server (misal validasi 400 atau 500)
                        return response.json().catch(() => {
                            // Jika response BUKAN JSON (misal HTML Error Page/500), kita throw error di sini
                            throw new Error("Server mengembalikan error non-JSON. Cek controller Anda.");
                        }).then(err => {
                            throw new Error(err.message || (err.debug_message ? err.debug_message : 'Error saat memproses keranjang')); 
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    runButtonAnimation(btnCart, 'cart', data.action);
                    updateBadgeCount('cart', data.count); 
                })
                .catch(error => {
                    if (error !== 'Unauthorized') { // Jangan tampilkan error jika hanya pengalihan login
                        console.error('Error Add to Cart:', error);
                        alert(`Gagal menambahkan ke keranjang: ${error.message || 'Terjadi kesalahan tidak terduga.'}`);
                    }
                });
            });
        }


        // --- C. Add to Wishlist Logic (AJAX SUBMISSION) ---
        if (btnWishlist) {
            btnWishlist.addEventListener('click', function() {
                const productId = this.dataset.id;
                
                fetch('{{ route("wishlist.store") }}', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest' // <-- INI TAMBAHAN UTAMA!
                    },
                    body: JSON.stringify({ product_id: productId })
                })
                .then(response => {
                    // Penanganan 401: Jika pengguna belum login
                    if (response.status === 401 || response.url.includes('login')) {
                        alert('Gagal menambahkan ke Wishlist: Silakan login terlebih dahulu.');
                        window.location.href = '{{ route("login.form") }}';
                        return Promise.reject('Unauthorized'); // Stop further processing
                    }

                    if (!response.ok) {
                        // Penanganan error dari server (422, 500)
                        return response.json().catch(() => {
                            // Menangkap error jika server mengembalikan HTML (misal 419/500 tanpa JSON)
                            throw new Error("Server mengembalikan respons non-JSON. Cek log server 500.");
                        }).then(err => { 
                             throw new Error(err.message || (err.debug_message ? err.debug_message : 'Gagal memperbarui Wishlist.')); 
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    runButtonAnimation(btnWishlist, 'wishlist', data.action);
                    updateBadgeCount('wishlist', data.count); 
                })
                .catch(error => {
                    if (error !== 'Unauthorized') { // Jangan tampilkan error jika hanya pengalihan login
                        console.error('Error Wishlist:', error);
                        alert(`Gagal memperbarui Wishlist: ${error.message}`);
                    }
                });
            });
        }
        
        // Inisialisasi tampilan awal
        updateSubtotal();
    });
</script>
</body>
</html>