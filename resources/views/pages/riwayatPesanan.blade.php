@extends('layouts.master')

@section('title', 'Status Pesanan — Buyee')

@php
    // $orders sekarang berisi array terstruktur dari OrderController
    $ordersArray = is_array($orders) ? $orders : [];
    
    $statusLabels = [
        'all' => 'Semua',
        'pending' => 'Menunggu Pembayaran',
        'diproses' => 'Diproses',
        'dikirim' => 'Dalam Pengiriman',
        'selesai' => 'Selesai',
        'batal' => 'Dibatalkan',
    ];
    $statusBadges = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'icon' => 'fa-clock'],
        'diproses' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-800', 'icon' => 'fa-cog'],
        'dikirim' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'icon' => 'fa-shipping-fast'],
        'selesai' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'icon' => 'fa-circle-check'],
        'batal' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'icon' => 'fa-circle-xmark'],
    ];
    
    // Hitung ulang status counts dari $ordersArray
    $statusCounts = collect($ordersArray)->groupBy(fn ($order) => $order['status'])->map->count();
    $statusCounts['all'] = count($ordersArray);

    // Filter array untuk menghindari error jika kunci tidak ada
    foreach ($statusLabels as $key => $label) {
        $statusCounts[$key] = $statusCounts[$key] ?? 0;
    }
@endphp

@section('content')
<main class="mx-auto max-w-7xl px-4 pb-24 pt-24 sm:px-6 lg:px-8">
    
    {{-- Breadcrumbs --}}
    <nav class="text-sm text-gray-500">
        <ol class="flex items-center gap-3">
            <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
            <li class="text-gray-300">›</li>
            <li class="text-gray-900">Status Pesanan</li>
        </ol>
    </nav>

    {{-- Pesan Sukses / Error --}}
    @if(session('success'))
        <div class="mt-6 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 text-emerald-700 shadow-sm">
            <div class="flex items-start gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-emerald-100 text-emerald-600">
                    <i class="fa-solid fa-circle-check"></i>
                </span>
                <div>
                    <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                    <p class="text-xs text-emerald-700/80">Cek detailnya di bawah.</p>
                </div>
            </div>
        </div>
    @endif
     @if(session('error'))
        <div class="mt-6 rounded-3xl border border-rose-200 bg-rose-50 p-5 text-rose-700 shadow-sm">
            <div class="flex items-start gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-rose-100 text-rose-600">
                    <i class="fa-solid fa-circle-xmark"></i>
                </span>
                <div>
                    <p class="text-sm font-semibold text-rose-800">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Header & Total --}}
    <div class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <h1 class="text-3xl font-extrabold text-gray-900">Status Pesanan Saya</h1>
        <p class="text-sm text-gray-500">Total Pesanan: <span class="font-semibold text-gray-800" data-count-display="all">{{ $statusCounts['all'] ?? 0 }}</span></p>
    </div>

    {{-- Tabs Filter --}}
    <div class="mt-8 overflow-x-auto">
        <div class="flex gap-3 border-b border-gray-200 pb-3" role="tablist">
            @foreach($statusLabels as $key => $label)
                <button
                    type="button"
                    data-filter="{{ $key }}"
                    class="group inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-medium transition {{ $key === 'all' ? 'border-black bg-black text-white' : 'border-gray-200 text-gray-600 hover:border-gray-300 hover:text-gray-900' }}"
                >
                    <span>{{ $label }}</span>
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-600 group-[.bg-black]:bg-white group-[.bg-black]:text-black" data-count-display="{{ $key }}">
                        {{ $statusCounts[$key] ?? 0 }}
                    </span>
                </button>
            @endforeach
        </div>
    </div>

    {{-- Order Cards Wrapper --}}
    <div class="mt-8 space-y-6" data-orders-wrapper>
        @forelse($ordersArray as $order)
            @php
                $badge = $statusBadges[$order['status']] ?? $statusBadges['pending'];
                
                // ✨ FIX HARGA: Ambil nilai integer, lalu hitung subtotal dan ongkir
                $subtotalProduct = collect($order['items'])->sum('subtotal_value');
                $deliveryFee = $order['total_value'] - $subtotalProduct;
            @endphp
            
            <article class="rounded-3xl border border-gray-200 bg-white shadow-sm" data-order-card data-status="{{ $order['status'] }}" data-raw-id="{{ $order['raw_id'] }}">
                {{-- Header --}}
                <div class="flex flex-col gap-4 border-b border-gray-100 p-6 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Order #{{ $order['id'] }}</h2>
                        <p class="text-xs text-gray-500">Dipesan pada {{ $order['date'] }}</p>
                        <p class="mt-2 text-xs text-gray-500">Metode bayar: <span class="font-medium text-gray-700 uppercase">{{ $order['payment_method'] }}</span></p>
                    </div>
                    <span class="inline-flex items-center gap-1.5 rounded-full {{ $badge['bg'] }} px-3 py-1 text-xs font-medium {{ $badge['text'] }}" data-status-badge>
                        <i class="fa-solid {{ $badge['icon'] }}"></i>
                        <span data-status-text>{{ $statusLabels[$order['status']] ?? 'Menunggu Pembayaran' }}</span>
                    </span>
                </div>

                {{-- Item List & Summary --}}
                <div class="p-6">
                    <div class="space-y-3">
                        {{-- Items --}}
                        @foreach(array_slice($order['items'], 0, 2) as $item)
                            @php
                                $imagePath = $item['image'];
                            @endphp
                            <div class="flex items-center gap-4 rounded-xl bg-gray-50 p-4">
                                <div class="h-16 w-16 overflow-hidden rounded-xl bg-white ring-1 ring-gray-200">
                                    <img src="{{ $imagePath }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';"/>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">{{ $item['name'] }}</p>
                                    <p class="text-xs text-gray-500">{{ $item['quantity'] }} × Rp {{ $item['price'] }}</p>
                                </div>
                                <span class="text-sm font-semibold text-gray-800">Rp {{ $item['subtotal'] }}</span>
                            </div>
                        @endforeach
                        @if(count($order['items']) > 2)
                            <p class="text-sm text-gray-500 text-center">+ {{ count($order['items']) - 2 }} produk lainnya</p>
                        @endif
                    </div>
                    
                    {{-- Total Summary --}}
                    <div class="mt-4 space-y-2 text-sm border-t border-gray-200 pt-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Total Produk</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($subtotalProduct, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ongkos Kirim</span>
                            <span class="font-medium text-gray-800">Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between border-t border-gray-200 pt-2">
                            <span class="font-semibold text-gray-900">Total Pembayaran</span>
                            <span class="font-semibold text-emerald-600">Rp {{ $order['total'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col gap-3 rounded-b-3xl bg-gray-50 p-6 sm:flex-row">
                    @if($order['status'] === 'pending' || $order['status'] === 'diproses')
                        <button type="button" class="flex-1 rounded-full bg-rose-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-600" data-action="cancel" data-order-id="{{ $order['raw_id'] }}" data-cancel-url="{{ route('orders.cancel', $order['raw_id']) }}">
                            Batalkan Pesanan
                        </button>
                    @endif
                    <button type="button" 
                        class="flex-1 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-300 hover:text-gray-900" 
                        data-action="detail" 
                        data-order-id="{{ $order['raw_id'] }}" 
                        data-detail-url="{{ route('orders.show', $order['raw_id']) }}">
                        Detail Pesanan
                    </button>
                    {{-- Tombol Konfirmasi Diterima hanya jika Dikirim --}}
                    @if($order['status'] === 'dikirim')
                        <button type="button" class="flex-1 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600" data-action="confirm" data-order-id="{{ $order['raw_id'] }}">
                            Konfirmasi Diterima
                        </button>
                    @endif
                </div>
            </article>
        @empty
            <div data-empty-state class="rounded-3xl border border-dashed border-gray-200 bg-white p-12 text-center">
                <i class="fa-solid fa-shopping-bag text-5xl text-gray-300"></i>
                <h3 class="mt-4 text-lg font-semibold text-gray-800">Belum Ada Pesanan</h3>
                <p class="mt-2 text-sm text-gray-500">Ayo mulailah berbelanja dan nikmati berbagai penawaran menarik.</p>
                <a href="{{ route('catalog') }}" class="mt-6 inline-flex items-center gap-2 rounded-full bg-black px-5 py-2 text-sm font-semibold text-white hover:bg-gray-900">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Lihat Katalog
                </a>
            </div>
        @endforelse
    </div>
</main>

{{-- MODAL DETAIL (Diletakkan di luar @section) --}}
<div id="order-detail-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-gray-900/60" data-modal-dismiss></div>
    <div class="relative z-10 mx-auto mt-20 w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">Detail Pesanan</p>
                <h3 class="text-lg font-semibold text-gray-900" id="detail-order-number"></h3>
                <p class="text-sm text-gray-500" id="detail-order-date"></p>
            </div>
            <button type="button" class="text-gray-400 transition hover:text-gray-600" data-modal-dismiss>
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <div class="mt-6 grid gap-6 md:grid-cols-2">
            <div class="space-y-2 text-sm">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Informasi Pelanggan</h4>
                <p class="text-gray-800" id="detail-customer-name"></p>
                <p class="text-gray-500" id="detail-customer-email"></p>
                <p class="text-gray-500" id="detail-customer-phone"></p>
            </div>
            <div class="space-y-2 text-sm">
                <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-400">Alamat Pengiriman</h4>
                <p class="text-gray-600" id="detail-address"></p>
                <p class="text-xs text-gray-400">Metode pembayaran: <span class="font-medium text-gray-700 uppercase" id="detail-payment-method"></span></p>
            </div>
        </div>
        
        <div class="mt-6 space-y-3" id="detail-items">
            {{-- Items akan diisi oleh JS --}}
        </div>

        <div class="mt-6 space-y-2 text-sm border-t border-gray-200 pt-2">
            <div class="flex justify-between">
                <span class="text-gray-500">Total Produk</span>
                <span class="font-medium text-gray-800" id="detail-subtotal-product"></span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-500">Ongkos Kirim</span>
                <span class="font-medium text-gray-800" id="detail-delivery-fee"></span>
            </div>
            <div class="flex items-center justify-between rounded-2xl bg-gray-50 px-4 py-3 text-sm">
                <span class="font-semibold text-gray-700">Total Pembayaran</span>
                <span class="font-semibold text-emerald-600" id="detail-total"></span>
            </div>
        </div>

        <p class="mt-4 text-xs text-gray-400" id="detail-notes"></p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        const filterButtons = document.querySelectorAll('[data-filter]');
        const ordersWrapper = document.querySelector('[data-orders-wrapper]');
        
        let orderCards = Array.from(document.querySelectorAll('[data-order-card]'));
        const emptyState = ordersWrapper?.querySelector('[data-empty-state]');

        const modal = document.getElementById('order-detail-modal');
        const detailNodes = {
            number: document.getElementById('detail-order-number'),
            date: document.getElementById('detail-order-date'),
            customerName: document.getElementById('detail-customer-name'),
            customerEmail: document.getElementById('detail-customer-email'),
            customerPhone: document.getElementById('detail-customer-phone'),
            address: document.getElementById('detail-address'),
            payment: document.getElementById('detail-payment-method'),
            total: document.getElementById('detail-total'),
            subtotalProduct: document.getElementById('detail-subtotal-product'),
            deliveryFee: document.getElementById('detail-delivery-fee'),
            notes: document.getElementById('detail-notes'),
            items: document.getElementById('detail-items'),
        };

        const statusLabels = {
            pending: 'Menunggu Pembayaran',
            diproses: 'Diproses',
            dikirim: 'Dalam Pengiriman',
            selesai: 'Selesai',
            batal: 'Dibatalkan',
        };
        const statusBadges = {
            pending: { bg: 'bg-yellow-100', text: 'text-yellow-800', icon: 'fa-clock' },
            diproses: { bg: 'bg-cyan-100', text: 'text-cyan-800', icon: 'fa-cog' },
            dikirim: { bg: 'bg-amber-100', text: 'text-amber-800', icon: 'fa-shipping-fast' },
            selesai: { bg: 'bg-emerald-100', text: 'text-emerald-800', icon: 'fa-circle-check' },
            batal: { bg: 'bg-rose-100', text: 'text-rose-800', icon: 'fa-circle-xmark' },
        };
        
        const toast = (message, type = 'success') => {
            document.querySelectorAll('.app-toast').forEach(el => el.remove());
            const el = document.createElement('div');
            el.textContent = message;
            el.className = `app-toast fixed left-1/2 top-8 z-[9999] -translate-x-1/2 rounded-full px-5 py-2 text-sm font-semibold shadow-lg transition ${type === 'success' ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'}`;
            document.body.appendChild(el);
            setTimeout(() => el.style.opacity = '1', 10);
            setTimeout(() => {
                el.style.opacity = '0';
                el.style.transition = 'opacity .4s ease';
            }, 2200);
            setTimeout(() => el.remove(), 2600);
        };

        const updateCounts = () => {
            const counts = { all: orderCards.length, pending: 0, diproses: 0, dikirim: 0, selesai: 0, batal: 0 };
            orderCards.forEach(card => {
                const status = card.dataset.status;
                if (status && status in counts) {
                    counts[status] += 1;
                }
            });
            document.querySelectorAll('[data-count-display]').forEach(span => {
                const key = span.dataset.countDisplay;
                if (key && key in counts) {
                    span.textContent = counts[key];
                }
            });
        };

        const applyFilter = (status) => {
            let hasVisible = false;
            orderCards.forEach(card => {
                const matches = status === 'all' || card.dataset.status === status;
                card.classList.toggle('hidden', !matches);
                if (matches) hasVisible = true;
            });
            
            if (emptyState) {
                emptyState.classList.toggle('hidden', hasVisible);
            }
        };

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-black', 'text-white', 'border-black');
                    btn.classList.add('border-gray-200', 'text-gray-600');
                });
                button.classList.add('bg-black', 'text-white', 'border-black');
                button.classList.remove('border-gray-200', 'text-gray-600');
                applyFilter(button.dataset.filter || 'all');
            });
        });

        const closeModal = () => {
            if (!modal) return;
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        modal?.querySelectorAll('[data-modal-dismiss]').forEach(el => el.addEventListener('click', closeModal));
        
        // Memuat data detail pesanan melalui AJAX untuk modal
        const fetchAndOpenModal = async (orderId, url) => {
             const button = document.querySelector(`[data-order-id="${orderId}"][data-action="detail"]`);
             button.disabled = true;
             
             try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) {
                    throw new Error('Gagal mengambil data detail pesanan.');
                }
                const data = await response.json();
                
                // Isi Node Modal
                detailNodes.number.textContent = data.order_number ?? '';
                detailNodes.date.textContent = data.date ?? '';
                detailNodes.customerName.textContent = data.customer_name ?? '';
                detailNodes.customerEmail.textContent = data.customer_email ?? '';
                detailNodes.customerPhone.textContent = data.customer_phone ?? '';
                detailNodes.address.textContent = data.address_text ?? '';
                detailNodes.payment.textContent = (data.payment_method ?? '').toUpperCase();
                detailNodes.total.textContent = data.total ?? '';
                detailNodes.subtotalProduct.textContent = data.subtotal_product ?? 'Rp 0';
                detailNodes.deliveryFee.textContent = data.delivery_fee ?? 'Rp 0';
                detailNodes.notes.textContent = data.order_notes ? `Catatan: ${data.order_notes}` : 'Tidak ada catatan.';

                detailNodes.items.innerHTML = '';
                (data.items || []).forEach(item => {
                    const image = item.image; 
                    
                    const wrapper = document.createElement('div');
                    wrapper.className = 'flex items-center gap-4 rounded-2xl bg-gray-50 p-4';
                    wrapper.innerHTML = `
                        <div class="h-16 w-16 overflow-hidden rounded-xl bg-white ring-1 ring-gray-200">
                            <img src="${image}" alt="${item.name}" class="h-full w-full object-cover" onerror="this.onerror=null;this.src='{{ asset('images/placeholder.jpg') }}';">
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">${item.name}</p>
                            <p class="text-xs text-gray-500">${item.quantity} × Rp ${item.price}</p>
                        </div>
                        <span class="text-sm font-semibold text-gray-800">Rp ${item.subtotal}</span>
                    `;
                    detailNodes.items.appendChild(wrapper);
                });

                document.body.classList.add('overflow-hidden');
                modal.classList.remove('hidden');

            } catch (error) {
                console.error('Fetch detail order error:', error);
                toast('Gagal memuat detail pesanan.', 'error');
            } finally {
                button.disabled = false;
            }
        };

        const updateCardStatus = (card, newStatus) => {
            // ... (Logika update status card dipertahankan)
            card.dataset.status = newStatus;
            const badge = card.querySelector('[data-status-badge]');
            const newBadgeInfo = statusBadges[newStatus];
            
            if (badge && newBadgeInfo) {
                badge.className = `inline-flex items-center gap-1.5 rounded-full ${newBadgeInfo.bg} px-3 py-1 text-xs font-medium ${newBadgeInfo.text}`;
                badge.querySelector('i').className = `fa-solid ${newBadgeInfo.icon}`;
                badge.querySelector('[data-status-text]').textContent = statusLabels[newStatus];
            }
            
            const actionsDiv = card.querySelector('.rounded-b-3xl');
            const confirmBtn = card.querySelector('[data-action="confirm"]');
            let cancelBtn = card.querySelector('[data-action="cancel"]');
            
            if (cancelBtn) cancelBtn.remove();
            if (confirmBtn) confirmBtn.remove();

            if (newStatus === 'dikirim') {
                 const btn = document.createElement('button');
                 btn.type = 'button';
                 btn.className = 'flex-1 rounded-full bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600';
                 btn.textContent = 'Konfirmasi Diterima';
                 btn.dataset.action = 'confirm';
                 btn.dataset.orderId = card.dataset.rawId;
                 const detailBtn = card.querySelector('[data-action="detail"]');
                 if (detailBtn) {
                    detailBtn.parentNode.insertBefore(btn, detailBtn.nextSibling);
                 } else {
                    actionsDiv.appendChild(btn);
                 }
            }
            
            if (newStatus === 'pending' || newStatus === 'diproses') {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'flex-1 rounded-full bg-rose-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-rose-600';
                btn.textContent = 'Batalkan Pesanan';
                btn.dataset.action = 'cancel';
                btn.dataset.orderId = card.dataset.rawId;
                btn.dataset.cancelUrl = "{{ route('orders.cancel', ['order' => '__ID__']) }}".replace('__ID__', card.dataset.rawId);
                actionsDiv.insertBefore(btn, actionsDiv.firstChild);
            }
        };


        const handleCancel = async (button) => {
            if (!confirm('Batalkan pesanan ini? Stok produk akan dikembalikan. Aksi ini tidak dapat dibatalkan.')) {
                return;
            }
            const orderId = button.dataset.orderId;
            const url = button.dataset.cancelUrl;
            if (!url || !orderId) return;

            button.disabled = true;
            const initialText = button.textContent;
            button.textContent = 'Memproses...';

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    credentials: 'same-origin',
                });

                const result = await response.json().catch(() => ({ message: 'Terjadi kesalahan.' }));
                const card = button.closest('[data-order-card]');

                if (!response.ok) {
                    toast(result.message || 'Gagal membatalkan pesanan.', 'error');
                } else {
                    toast(result.message || 'Pesanan dibatalkan.');
                    if (card) {
                        updateCardStatus(card, 'batal'); 
                        orderCards = Array.from(document.querySelectorAll('[data-order-card]'));
                    }
                }

            } catch (error) {
                console.error('Cancel order error:', error);
                toast('Terjadi kesalahan koneksi. Silakan coba lagi.', 'error');
            } finally {
                button.disabled = false;
                button.textContent = initialText;
                updateCounts();
                const activeFilter = document.querySelector('[data-filter].bg-black')?.dataset.filter || 'all';
                applyFilter(activeFilter);
            }
        };
        
        const handleConfirm = async (button) => {
            if (!confirm('Anda yakin telah menerima pesanan ini? Status akan diubah menjadi Selesai.')) {
                return;
            }
            const orderId = button.dataset.orderId;
            
            button.disabled = true;
            const initialText = button.textContent;
            button.textContent = 'Memproses...';

            try {
                // Menggunakan endpoint Admin Controller untuk mengubah status ke 'selesai'
                const response = await fetch('/buyee-admin/orders/update-status', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ id: orderId, status: 'selesai' })
                });

                const result = await response.json().catch(() => ({ message: 'Terjadi kesalahan.' }));
                const card = button.closest('[data-order-card]');

                if (!response.ok) {
                    toast(result.message || 'Gagal konfirmasi pesanan.', 'error');
                } else {
                    toast('Pesanan Selesai! Terima kasih.', 'success');
                    if (card) {
                        updateCardStatus(card, 'selesai');
                    }
                }

            } catch (error) {
                console.error('Confirm order error:', error);
                toast('Terjadi kesalahan koneksi. Silakan coba lagi.', 'error');
            } finally {
                button.disabled = false;
                button.textContent = initialText;
                updateCounts();
                const activeFilter = document.querySelector('[data-filter].bg-black')?.dataset.filter || 'all';
                applyFilter(activeFilter);
            }
        };

        ordersWrapper?.addEventListener('click', (event) => {
            const target = event.target.closest('[data-action]');
            if (!target) return;

            const action = target.dataset.action;
            const orderId = target.dataset.orderId;

            if (action === 'detail') {
                const url = target.dataset.detailUrl;
                if (url && orderId) {
                    fetchAndOpenModal(orderId, url);
                }
            }

            if (action === 'cancel') {
                handleCancel(target);
            }
            
            if (action === 'confirm') {
                handleConfirm(target);
            }
        });
        
        // Inisiasi awal
        applyFilter('all');
        updateCounts();
    });
</script>
@endpush