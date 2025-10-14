@extends('layouts.master')

@section('title', 'Checkout — Buyee')

@section('content')
<main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 pb-24">
  <div class="flex flex-col gap-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ route('cart.index') }}"
           class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:border-gray-300 hover:bg-gray-50">
          <i class="fa-solid fa-arrow-left text-xs"></i>
          Kembali ke Keranjang
        </a>
        <h1 class="text-3xl font-extrabold text-gray-900">Checkout Pesanan</h1>
      </div>
      <p class="text-sm text-gray-500">Langkah terakhir sebelum barang favoritmu dikirim.</p>
    </div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
      {{-- Form Checkout --}}
      <section class="lg:col-span-2">
        <form id="checkoutForm"
              action="{{ route('checkout.store') }}"
              method="POST"
              class="space-y-10">
          @csrf

          {{-- Data Pelanggan --}}
          <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center gap-3">
              <span class="grid h-10 w-10 place-items-center rounded-full bg-pink-100 text-pink-600">
                <i class="fa-solid fa-user"></i>
              </span>
              <div>
                <h2 class="text-lg font-semibold text-gray-900">Data Pelanggan</h2>
                <p class="text-sm text-gray-500">Informasi ini memudahkan kami menghubungimu.</p>
              </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">
              <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-gray-700">Nama Lengkap *</span>
                <input type="text"
                       name="customer_name"
                       required
                       value="{{ old('customer_name', 'John Doe') }}"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60">
              </label>
              <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-gray-700">Email *</span>
                <input type="email"
                       name="customer_email"
                       required
                       value="{{ old('customer_email', 'john@example.com') }}"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60">
              </label>
              <label class="md:col-span-2 flex flex-col gap-2">
                <span class="text-sm font-medium text-gray-700">Nomor Telepon *</span>
                <input type="tel"
                       name="customer_phone"
                       required
                       placeholder="08xxxxxxxxxx"
                       class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60">
              </label>
            </div>
          </div>

          {{-- Alamat Pengiriman --}}
          <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center gap-3">
              <span class="grid h-10 w-10 place-items-center rounded-full bg-pink-100 text-pink-600">
                <i class="fa-solid fa-location-dot"></i>
              </span>
              <div>
                <h2 class="text-lg font-semibold text-gray-900">Alamat Pengiriman</h2>
                <p class="text-sm text-gray-500">Isi sesuai lokasi pengiriman pesanan.</p>
              </div>
            </div>

            <div class="mt-6 flex flex-col gap-5">
              <label class="flex flex-col gap-2">
                <span class="text-sm font-medium text-gray-700">Alamat Lengkap *</span>
                <textarea name="shipping_address"
                          rows="3"
                          required
                          placeholder="Nama jalan, nomor rumah, RT/RW, detail lain"
                          class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60"></textarea>
              </label>

              <div class="grid grid-cols-1 gap-5 md:grid-cols-3">
                <label class="flex flex-col gap-2">
                  <span class="text-sm font-medium text-gray-700">Kota *</span>
                  <input type="text"
                         name="city"
                         required
                         placeholder="Nama kota"
                         class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60">
                </label>
                <label class="flex flex-col gap-2">
                  <span class="text-sm font-medium text-gray-700">Provinsi *</span>
                  <select name="province"
                          required
                          class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60">
                    <option value="">Pilih Provinsi</option>
                    @foreach (['Jawa Tengah', 'Jawa Barat', 'Jawa Timur', 'DKI Jakarta', 'Yogyakarta'] as $province)
                      <option value="{{ $province }}">{{ $province }}</option>
                    @endforeach
                  </select>
                </label>
                <label class="flex flex-col gap-2">
                  <span class="text-sm font-medium text-gray-700">Kode Pos *</span>
                  <input type="text"
                         name="postal_code"
                         required
                         maxlength="5"
                         placeholder="12345"
                         class="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60">
                </label>
              </div>
            </div>
          </div>

          {{-- Metode Pembayaran --}}
          <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center gap-3">
              <span class="grid h-10 w-10 place-items-center rounded-full bg-pink-100 text-pink-600">
                <i class="fa-solid fa-credit-card"></i>
              </span>
              <div>
                <h2 class="text-lg font-semibold text-gray-900">Metode Pembayaran</h2>
                <p class="text-sm text-gray-500">Pilih cara pembayaran yang paling nyaman untukmu.</p>
              </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
              @php
                $payments = [
                  ['id' => 'cod', 'label' => 'Cash On Delivery', 'description' => 'Bayar saat pesanan tiba', 'icon' => 'fa-truck', 'accent' => 'text-green-500'],
                  ['id' => 'transfer', 'label' => 'Transfer Bank', 'description' => 'Transfer ke rekening toko', 'icon' => 'fa-building-columns', 'accent' => 'text-blue-500'],
                  ['id' => 'qris', 'label' => 'QRIS', 'description' => 'Scan QR code instan', 'icon' => 'fa-qrcode', 'accent' => 'text-purple-500'],
                ];
              @endphp

              @foreach ($payments as $index => $payment)
                <label class="relative flex cursor-pointer flex-col gap-3 rounded-2xl border border-gray-200 bg-white/80 p-4 shadow-sm transition hover:border-pink-200 hover:shadow-md">
                  <input type="radio"
                         name="payment_method"
                         value="{{ $payment['id'] }}"
                         {{ $index === 0 ? 'checked' : '' }}
                         class="peer absolute inset-0 h-full w-full cursor-pointer opacity-0">
                  <div class="flex items-start gap-3">
                    <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-pink-100 text-pink-600 transition peer-checked:bg-pink-500 peer-checked:text-white">
                      <i class="fa-solid {{ $payment['icon'] }}"></i>
                    </span>
                    <div>
                      <p class="text-sm font-semibold text-gray-900">{{ $payment['label'] }}</p>
                      <p class="text-xs text-gray-500">{{ $payment['description'] }}</p>
                    </div>
                  </div>
                  <span class="pointer-events-none absolute inset-0 hidden rounded-2xl border-2 border-pink-500/70 transition peer-checked:block"></span>
                </label>
              @endforeach
            </div>

            <div id="transferInfo" class="mt-5 hidden rounded-2xl border border-blue-100 bg-blue-50/70 p-4 text-sm text-blue-800">
              <p class="font-semibold text-blue-900">Informasi Transfer</p>
              <ul class="mt-2 space-y-1 text-blue-800/90">
                <li>Bank BCA — <span class="font-semibold">1234567890</span></li>
                <li>Atas Nama — <span class="font-semibold">PT Buyee Indonesia</span></li>
                <li>Kirim sesuai total pesanan dan unggah bukti melalui profilmu.</li>
              </ul>
            </div>
          </div>

          {{-- Catatan --}}
          <div class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center gap-3">
              <span class="grid h-10 w-10 place-items-center rounded-full bg-pink-100 text-pink-600">
                <i class="fa-solid fa-note-sticky"></i>
              </span>
              <div>
                <h2 class="text-lg font-semibold text-gray-900">Catatan Pesanan</h2>
                <p class="text-sm text-gray-500">Beritahu kami preferensimu (opsional).</p>
              </div>
            </div>

            <textarea name="order_notes"
                      rows="3"
                      placeholder="Contoh: Mohon kirimkan di jam kerja / Tolong hubungi sebelum sampai."
                      class="mt-6 w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-pink-300 focus:outline-none focus:ring-4 focus:ring-pink-200/60"></textarea>
          </div>
        </form>
      </section>

      {{-- Ringkasan Pesanan --}}
      <aside class="flex flex-col gap-6">
        <div class="rounded-3xl bg-gradient-to-br from-pink-400 via-pink-500 to-pink-600 p-6 text-white shadow-lg shadow-pink-200">
          <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-full bg-white/20">
              <i class="fa-solid fa-cart-shopping"></i>
            </span>
            <h2 class="text-lg font-semibold">Ringkasan Pesanan</h2>
          </div>

          <div class="mt-5 space-y-4">
            @foreach($cartItems as $item)
              <div class="rounded-2xl bg-white/90 p-4 text-gray-800 shadow-sm shadow-pink-200/50">
                <div class="flex gap-3">
                  <div class="h-16 w-16 shrink-0 overflow-hidden rounded-xl bg-gray-100 ring-1 ring-pink-100">
                    @if($item->product->images && count($item->product->images) > 0)
                      <img src="{{ asset('storage/' . $item->product->images[0]) }}"
                           alt="{{ $item->product->name }}"
                           class="h-full w-full object-cover">
                    @else
                      <div class="grid h-full w-full place-items-center text-sm text-gray-400">
                        <i class="fa-regular fa-image"></i>
                      </div>
                    @endif
                  </div>
                  <div class="flex flex-1 flex-col">
                    <p class="text-sm font-semibold text-gray-900">{{ $item->product->name }}</p>
                    <p class="text-xs text-gray-500">{{ $item->product->category->name ?? 'Kategori tidak tersedia' }}</p>
                    <div class="mt-2 flex items-center justify-between text-sm">
                      <span class="text-gray-600">{{ $item->quantity }} × Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                      <span class="font-semibold text-gray-900">Rp {{ number_format($item->quantity * $item->product->price, 0, ',', '.') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <div class="mt-6 space-y-3 text-sm">
            <div class="flex items-center justify-between">
              <span class="text-white/80">Subtotal</span>
              <span class="font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex items-center justify-between rounded-2xl bg-white/15 px-4 py-3 text-base font-semibold">
              <span>Total Pembayaran</span>
              <span class="js-total-amount">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
            <p class="text-right text-xs text-white/70">Gratis ongkir untuk setiap pesanan.</p>
          </div>
          <div class="mt-6 space-y-3 text-xs text-white/80">
          </div>
        </div>

        <button type="submit"
                form="checkoutForm"
                id="checkoutSubmit"
                class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-black px-6 py-4 text-base font-semibold text-white shadow-lg shadow-gray-900/20 transition hover:bg-gray-900 focus:outline-none focus:ring-4 focus:ring-pink-400/40">
          <span>Konfirmasi &amp; Buat Pesanan</span>
          <i class="fa-solid fa-arrow-right"></i>
        </button>
        <p class="text-center text-xs text-gray-500">Dengan menekan tombol di atas, kamu menyetujui syarat &amp; ketentuan Buyee.</p>
      </aside>
    </div>
  </div>
</main>

{{-- Modal Transfer --}}
<div id="modal-transfer" data-modal class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-gray-900/60" data-modal-close></div>
  <div class="relative z-10 mx-auto mt-32 w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl">
    <div class="flex items-start justify-between">
      <div class="flex items-center gap-3">
        <span class="grid h-11 w-11 place-items-center rounded-full bg-blue-100 text-blue-600">
          <i class="fa-solid fa-building-columns"></i>
        </span>
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Selesaikan Pembayaran Transfer</h3>
          <p class="text-sm text-gray-500">Gunakan informasi berikut untuk menyelesaikan pembayaranmu.</p>
        </div>
      </div>
      <button type="button" class="text-gray-400 transition hover:text-gray-600" data-modal-close>
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="mt-6 space-y-4 rounded-2xl bg-gray-50 p-5">
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-500">Nomor Pesanan</span>
        <span id="transferOrderId" class="font-semibold text-gray-900"></span>
      </div>
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-500">Total Pembayaran</span>
        <span id="transferTotalAmount" class="font-semibold text-gray-900"></span>
      </div>
      <div class="rounded-xl bg-white p-4 text-sm text-gray-700 shadow-sm">
        <p class="font-semibold text-gray-900">Transfer ke:</p>
        <p class="mt-2">Bank BCA • 1234567890</p>
        <p>a.n PT Buyee Indonesia</p>
      </div>
    </div>
    <p class="mt-6 text-sm text-gray-500">Unggah bukti transfer melalui halaman Riwayat Pesanan agar pesananmu diproses lebih cepat.</p>
    <button type="button" class="mt-6 w-full rounded-full bg-blue-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-400/40" data-modal-close>
      Saya Sudah Transfer
    </button>
  </div>
</div>

{{-- Modal COD --}}
<div id="modal-cod" data-modal class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-gray-900/60" data-modal-close></div>
  <div class="relative z-10 mx-auto mt-32 w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl">
    <div class="flex items-start justify-between">
      <div class="flex items-center gap-3">
        <span class="grid h-11 w-11 place-items-center rounded-full bg-green-100 text-green-600">
          <i class="fa-solid fa-truck-fast"></i>
        </span>
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Pesanan COD Dibuat</h3>
          <p class="text-sm text-gray-500">Kurir kami akan menghubungimu sebelum pengantaran.</p>
        </div>
      </div>
      <button type="button" class="text-gray-400 transition hover:text-gray-600" data-modal-close>
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <p class="mt-6 text-sm text-gray-600">Siapkan uang tunai sesuai total pesanan dan pastikan nomor teleponmu aktif agar kurir mudah menghubungi.</p>
    <button type="button" class="mt-6 w-full rounded-full bg-green-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-green-600 focus:outline-none focus:ring-4 focus:ring-green-400/40" data-modal-close>
      Mengerti, Terima Kasih
    </button>
  </div>
</div>

{{-- Modal QRIS --}}
<div id="modal-qris" data-modal class="fixed inset-0 z-50 hidden">
  <div class="absolute inset-0 bg-gray-900/60" data-modal-close></div>
  <div class="relative z-10 mx-auto mt-24 w-full max-w-lg rounded-3xl bg-white p-8 shadow-2xl">
    <div class="flex items-start justify-between">
      <div class="flex items-center gap-3">
        <span class="grid h-11 w-11 place-items-center rounded-full bg-purple-100 text-purple-600">
          <i class="fa-solid fa-qrcode"></i>
        </span>
        <div>
          <h3 class="text-lg font-semibold text-gray-900">Scan QRIS untuk Membayar</h3>
          <p class="text-sm text-gray-500">Gunakan aplikasi bank atau dompet digital favoritmu.</p>
        </div>
      </div>
      <button type="button" class="text-gray-400 transition hover:text-gray-600" data-modal-close>
        <i class="fa-solid fa-xmark"></i>
      </button>
    </div>
    <div class="mt-6 flex flex-col items-center gap-4 rounded-2xl bg-gray-50 p-5">
      <img src="{{ asset('images/placeholder.jpg') }}"
           alt="QRIS Buyee"
           class="h-56 w-56 rounded-2xl border border-gray-200 object-cover shadow-inner">
      <div class="text-center">
        <p class="text-sm text-gray-500">Total Pembayaran</p>
        <p id="qrisTotalAmount" class="text-xl font-semibold text-gray-900"></p>
      </div>
    </div>
    <p class="mt-6 text-sm text-gray-500">Pesanan diproses otomatis setelah pembayaran terdeteksi. Jika butuh bantuan, hubungi kami melalui menu Bantuan.</p>
    <button type="button" class="mt-6 w-full rounded-full bg-purple-500 px-5 py-3 text-sm font-semibold text-white transition hover:bg-purple-600 focus:outline-none focus:ring-4 focus:ring-purple-400/40" data-modal-close>
      Saya Mengerti
    </button>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const checkoutForm = document.getElementById('checkoutForm');
    const submitBtn = document.getElementById('checkoutSubmit');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    submitBtn.dataset.originalLabel = submitBtn.innerHTML;

    const modals = {
      transfer: document.getElementById('modal-transfer'),
      cod: document.getElementById('modal-cod'),
      qris: document.getElementById('modal-qris'),
    };

    const transferInfo = document.getElementById('transferInfo');
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');

    const showToast = (message, type = 'success') => {
      document.querySelectorAll('.app-toast').forEach(el => el.remove());
      const toast = document.createElement('div');
      toast.textContent = message;
      toast.className = `app-toast fixed left-1/2 top-8 z-[9999] -translate-x-1/2 rounded-full px-5 py-2 text-sm font-semibold shadow-lg transition ${
        type === 'success'
          ? 'bg-emerald-500 text-white'
          : 'bg-rose-500 text-white'
      }`;
      document.body.appendChild(toast);
      requestAnimationFrame(() => {
        toast.style.opacity = '1';
      });
      setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity .4s ease';
      }, 2200);
      setTimeout(() => toast.remove(), 2600);
    };

    const toggleTransferInfo = () => {
      if (!transferInfo) return;
      const selected = document.querySelector('input[name="payment_method"]:checked');
      if (selected && selected.value === 'transfer') {
        transferInfo.classList.remove('hidden');
      } else {
        transferInfo.classList.add('hidden');
      }
    };

    paymentRadios.forEach(radio => radio.addEventListener('change', toggleTransferInfo));
    toggleTransferInfo();

    const closeModal = (modal) => {
      modal.classList.add('hidden');
      document.body.classList.remove('overflow-hidden');
      submitBtn.disabled = false;
      submitBtn.innerHTML = submitBtn.dataset.originalLabel;

      if (modal.dataset.resetForm === 'true') {
        checkoutForm.reset();
        toggleTransferInfo();
      }

      const redirectTarget = modal.dataset.redirect;
      modal.dataset.redirect = '';
      if (redirectTarget) {
        window.location.href = redirectTarget;
      }
    };

    document.querySelectorAll('[data-modal-close]').forEach(trigger => {
      trigger.addEventListener('click', () => {
        const modal = trigger.closest('[data-modal]');
        if (modal) closeModal(modal);
      });
    });

    const showModal = (key, redirectUrl = '') => {
      const modal = modals[key];
      if (!modal) {
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitBtn.dataset.originalLabel;
        if (redirectUrl) {
          window.location.href = redirectUrl;
        }
        return;
      }
      document.body.classList.add('overflow-hidden');
      modal.dataset.resetForm = 'true';
      modal.dataset.redirect = redirectUrl;
      modal.classList.remove('hidden');
    };

    const setLoadingState = () => {
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <span class="flex items-center gap-3">
          <span class="h-5 w-5 animate-spin rounded-full border-2 border-white/40 border-t-white"></span>
          Memproses Pesanan...
        </span>`;
    };

    const clearValidationState = (field) => {
      field.classList.remove('ring-2', 'ring-rose-400', 'border-rose-400');
    };

    const setInvalid = (field) => {
      field.classList.add('ring-2', 'ring-rose-400', 'border-rose-400');
    };

    checkoutForm.querySelectorAll('input, select, textarea').forEach(field => {
      field.addEventListener('input', () => clearValidationState(field));
    });

    checkoutForm.addEventListener('submit', async (event) => {
      event.preventDefault();

      let isValid = true;
      const requiredFields = checkoutForm.querySelectorAll('[required]');

      requiredFields.forEach(field => {
        if (!field.value.trim()) {
          isValid = false;
          setInvalid(field);
        } else {
          clearValidationState(field);
        }
      });

      const phone = checkoutForm.querySelector('[name="customer_phone"]');
      if (phone.value && !/^08[0-9]{8,11}$/.test(phone.value)) {
        isValid = false;
        setInvalid(phone);
      }

      const postal = checkoutForm.querySelector('[name="postal_code"]');
      if (postal.value && !/^[0-9]{5}$/.test(postal.value)) {
        isValid = false;
        setInvalid(postal);
      }

      if (!isValid) {
        showToast('Mohon lengkapi data wajib dengan format yang benar.', 'error');
        return;
      }

      setLoadingState();

      try {
        const formData = new FormData(checkoutForm);
        const response = await fetch(checkoutForm.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
          },
          credentials: 'same-origin',
          body: formData,
        });

        const result = await response.json().catch(() => ({ message: 'Terjadi kesalahan tak terduga.' }));

        if (!response.ok) {
          if (response.status === 422 && result.errors) {
            const [[field, messages]] = Object.entries(result.errors);
            const input = checkoutForm.querySelector(`[name="${field}"]`);
            if (input) setInvalid(input);
            showToast(messages[0] ?? 'Data tidak valid.', 'error');
          } else {
            showToast(result.message ?? 'Gagal memproses pesanan.', 'error');
          }
          submitBtn.disabled = false;
          submitBtn.innerHTML = submitBtn.dataset.originalLabel;
          return;
        }

        showToast(result.message ?? 'Pesanan berhasil dibuat!');

        const paymentMethod = checkoutForm.querySelector('input[name="payment_method"]:checked')?.value || result.payment_method || 'cod';
        const totalAmount = document.querySelector('.js-total-amount')?.textContent.trim() ?? '-';

        if (paymentMethod === 'transfer') {
          document.getElementById('transferOrderId').textContent = result.order_number ?? `INV-${Date.now().toString().slice(-6)}`;
          document.getElementById('transferTotalAmount').textContent = totalAmount;
        }

        if (paymentMethod === 'qris') {
          document.getElementById('qrisTotalAmount').textContent = totalAmount;
        }

        showModal(paymentMethod, result.redirect || '{{ route('orders.index') }}');
      } catch (error) {
        console.error('Checkout error:', error);
        showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
        submitBtn.disabled = false;
        submitBtn.innerHTML = submitBtn.dataset.originalLabel;
      }
    });
  });
</script>
@endpush
