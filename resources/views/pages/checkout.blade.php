<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Buyee Mini-Commerce</title>
    {{-- Menggunakan Tailwind/Vite untuk styling utama --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Asumsi: Anda mengkompilasi file CSS/JS Anda melalui Vite --}}
    @vite(['resources/css/app.css','resources/js/app.js']) 

    <style>
        /* CSS Tambahan untuk Styling Khusus Checkout */
        body {
            background: #f7f7f7;
            font-family: 'Inter', sans-serif;
            color: #1f2937; /* Gray-800 */
        }
        .checkout-container {
            padding: 2rem 0;
        }
        .checkout-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08); /* Shadow halus */
            border: 1px solid #e5e7eb; /* Border tipis */
        }
        .section-header {
            color: #1f2937; 
            font-weight: 700;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #d1d5db; /* Gray-300 */
        }
        .summary-card {
            /* Warna Summary Card Diperkuat dengan Pink/Ungu */
            background: linear-gradient(135deg, #FFB6C1 0%, #FFC0CB 100%);
            color: #1f2937; /* Kontras gelap */
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(255, 182, 193, 0.3);
        }
        .summary-card .section-header {
             border-bottom: 1px solid rgba(0, 0, 0, 0.1);
             color: #1f2937; 
        }
        .btn-checkout {
            /* Button Checkout: Warna hitam/aksen yang kontras */
            background: #1f2937;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-checkout:hover {
            background: #374151;
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
        .alert-info {
            /* Alert Info untuk notifikasi */
            background-color: #e0f2fe; /* Light Blue/Cyan */
            border: 1px solid #93c5fd;
            color: #0c4a6e;
        }
        /* Style untuk Modal Transfer/QRIS */
        .modal-header {
            background: #FFB6C1; /* Indigo/Ungu */
            color: white;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        {{-- Tautan kembali menggunakan kelas netral --}}
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <h2 class="mb-0">
                            {{-- Ikon Checkout: Warna netral/primary --}}
                            <i class="fas fa-credit-card text-indigo-600 me-2"></i>
                            Checkout Pesanan
                        </h2>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Pastikan data pesanan dan alamat pengiriman sudah benar sebelum melanjutkan.
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card checkout-card mb-4">
                        <div class="card-body p-4">
                            <form id="checkoutForm" method="POST" action=""> 
                                @csrf 
                                
                                <h5 class="section-header">
                                    <i class="fas fa-user text-indigo-600 me-2"></i>
                                    Data Pelanggan
                                </h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap *</label>
                                        <input type="text" class="form-control" name="customer_name" value="{{ Auth::user()->name }}" required readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="customer_email" value="{{ Auth::user()->email }}" required readonly>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Telepon *</label>
                                        <input type="tel" class="form-control" name="customer_phone" placeholder="08xxxxxxxxxx" required>
                                    </div>
                                </div>

                                <h5 class="section-header">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    Alamat Pengiriman
                                </h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap *</label>
                                    <textarea class="form-control" name="shipping_address" rows="3" placeholder="Jalan, Nomor Rumah, RT/RW" required></textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Kota *</label>
                                        <input type="text" class="form-control" name="city" placeholder="Nama Kota" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Provinsi *</label>
                                        <select class="form-select" name="province" required>
                                            <option value="">Pilih Provinsi</option>
                                            <option value="Jawa Tengah">Jawa Tengah</option>
                                            <option value="Jawa Barat">Jawa Barat</option>
                                            <option value="Jawa Timur">Jawa Timur</option>
                                            <option value="DKI Jakarta">DKI Jakarta</option>
                                            <option value="Yogyakarta">Yogyakarta</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Kode Pos *</label>
                                        <input type="text" class="form-control" name="postal_code" placeholder="12345" maxlength="5" required>
                                    </div>
                                </div>

                                <h5 class="section-header">
                                    <i class="fas fa-money-check text-warning me-2"></i>
                                    Metode Pembayaran
                                </h5>
                                
                                <div class="row mb-4">
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check p-3 border rounded h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                            <label class="form-check-label" for="cod">
                                                <i class="fas fa-truck text-success me-2"></i>
                                                <strong>Cash On Delivery (COD)</strong>
                                                <br><small class="text-muted">Bayar saat barang diterima</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check p-3 border rounded h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="transfer" value="transfer">
                                            <label class="form-check-label" for="transfer">
                                                <i class="fas fa-university text-indigo-600 me-2"></i>
                                                <strong>Transfer Bank</strong>
                                                <br><small class="text-muted">Transfer ke rekening toko</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check p-3 border rounded h-100">
                                            <input class="form-check-input" type="radio" name="payment_method" id="qris" value="qris">
                                            <label class="form-check-label" for="qris">
                                                <i class="fas fa-qrcode text-info me-2"></i>
                                                <strong>QRIS</strong>
                                                <br><small class="text-muted">Bayar dengan scan QR code</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="section-header">
                                    <i class="fas fa-sticky-note text-info me-2"></i>
                                    Catatan Pesanan
                                </h5>
                                
                                <div class="mb-3">
                                    <textarea class="form-control" name="order_notes" rows="3" placeholder="Catatan tambahan untuk penjual (opsional)"></textarea>
                                </div>
                                
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card checkout-card">
                        <div class="card-body summary-card p-4">
                            <h5 class="section-header text-white border-bottom border-light">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Ringkasan Pesanan
                            </h5>
                            
                            <div class="cart-items mb-4">
                                @foreach ($cartItems as $item)
                                @php
                                    $itemSubtotal = $item->quantity * $item->product->price;
                                    $productImage = $item->product->image ? '/' . $item->product->image : asset('images/placeholder.jpg'); 
                                @endphp
                                <div class="cart-item bg-white text-dark">
                                    <div class="d-flex align-items-center">
                                        <div class="product-image me-3 " style="width: 180px; height: 180px;">
                                            <img src="{{ $productImage }}" alt="{{ $item->product->name }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 fw-bold fs-5">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Kategori: {{ $item->product->category->name ?? 'N/A' }}</small>
                                            <div class="d-flex justify-content-between mt-1">
                                                <span>{{ $item->quantity }} x Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                                {{-- <strong>Rp {{ number_format($itemSubtotal, 0, ',', '.') }}</strong> --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="border-top border-light pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkos Kirim:</span>
                                    <span>Rp {{ number_format($deliveryFee, 0, ',', '.') }}</span>
                                </div>
                                <hr class="border-light">
                                <div class="d-flex justify-content-between mb-4">
                                    <strong class="h5">Total:</strong>
                                    <strong class="h5 total-amount">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                                </div>
                                
                                <button type="submit" form="checkoutForm" class="btn btn-checkout w-100 btn-lg">
                                    <i class="fas fa-check me-2"></i>
                                    Buat Pesanan
                                </button>
                                
                                <div class="text-center mt-3">
                                    <small class="text-light">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Pesanan Anda aman dan terjamin
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="transferModal" tabindex="-1" aria-labelledby="transferModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transferModalLabel"><i class="fas fa-hourglass-half me-2"></i>Status Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <i class="fas fa-check-circle text-success fa-3x"></i>
                        <h5 class="mt-2">Pesanan Berhasil Dibuat!</h5>
                    </div>
                    <p class="text-center">Silakan selesaikan pembayaran Anda untuk melanjutkan.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Nomor Pesanan:
                            <strong id="transferOrderId">INV-{{ time() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Total Pembayaran:
                            <strong id="transferTotalAmount" class="text-danger">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                        </li>
                        <li class="list-group-item">
                            <strong>Informasi Transfer:</strong><br>
                            <small>Bank BCA: <strong>1234567890</strong><br>
                            A.n: <strong>UMKM Mini-Commerce</strong></small>
                        </li>
                    </ul>
                    <p class="text-muted text-center mt-3 small">Pesanan akan diproses setelah pembayaran dikonfirmasi.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="codSuccessModal" tabindex="-1" aria-labelledby="codSuccessModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="codSuccessModalLabel"><i class="fas fa-box-check me-2"></i>Pesanan Berhasil Dibuat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <i class="fas fa-truck-fast text-success fa-3x mb-3"></i>
                    <h5>Terima Kasih!</h5>
                    <p>Pesanan Anda dengan metode <strong>Cash on Delivery (COD)</strong> telah kami terima dan akan segera kami proses. Mohon siapkan pembayaran tunai saat kurir tiba.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="qrisModal" tabindex="-1" aria-labelledby="qrisModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrisModalLabel"><i class="fas fa-qrcode me-2"></i>Selesaikan Pembayaran QRIS</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p>Scan QR code di bawah ini untuk membayar pesanan Anda:</p>
                    
                    <img src="{{ asset('images/qris-dummy.jpeg') }}" alt="QRIS Code Toko" class="img-fluid rounded mb-3" style="max-width: 250px;">
                    
                    <h5 class="mt-2">Total Pembayaran:</h5>
                    <h4 id="qrisTotalAmount" class="text-danger fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                    
                    <p class="text-muted small mt-3">Pesanan akan diproses secara otomatis setelah pembayaran berhasil.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary w-100" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkoutForm = document.getElementById('checkoutForm');
            const totalAmountEl = document.querySelector('.total-amount');

            // --- Form Submission Handler ---
            checkoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Basic validation
                const requiredFields = this.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });
                
                // Phone validation
                const phone = document.querySelector('[name="customer_phone"]');
                const phoneRegex = /^08[0-9]{8,11}$/;
                if (phone.value && !phoneRegex.test(phone.value)) {
                    phone.classList.add('is-invalid');
                    isValid = false;
                }
                
                // Postal code validation
                const postalCode = document.querySelector('[name="postal_code"]');
                const postalRegex = /^[0-9]{5}$/;
                if (postalCode.value && !postalRegex.test(postalCode.value)) {
                    postalCode.classList.add('is-invalid');
                    isValid = false;
                }
                
                if (isValid) {
                    const submitBtn = document.querySelector('button[form="checkoutForm"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                    
                    // Simulate processing (Replace with your actual API call)
                    setTimeout(() => {
                        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                        const totalAmount = totalAmountEl.textContent.trim(); 

                        // === LOGIKA TAMPIL MODAL BERDASARKAN METODE PEMBAYARAN ===
                        if (paymentMethod === 'transfer') {
                            // Update modal transfer dan tampilkan
                            document.getElementById('transferTotalAmount').textContent = totalAmount;
                            document.getElementById('transferOrderId').textContent = 'INV-' + Date.now(); // Contoh ID
                            const transferModal = new bootstrap.Modal(document.getElementById('transferModal'));
                            transferModal.show();
                        } else if (paymentMethod === 'qris') { 
                            // Update modal qris dan tampilkan
                            document.getElementById('qrisTotalAmount').textContent = totalAmount;
                            const qrisModal = new bootstrap.Modal(document.getElementById('qrisModal'));
                            qrisModal.show();
                        } else { // COD
                            // Tampilkan modal COD Success
                            const codModal = new bootstrap.Modal(document.getElementById('codSuccessModal'));
                            codModal.show();
                        }

                        // Add event listener to reset button when any modal is closed
                        document.querySelectorAll('.modal').forEach(modalEl => {
                            modalEl.addEventListener('hidden.bs.modal', () => {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = originalText;
                                // Hapus redirect atau set window.location.href = '{{ route("orders.index") }}'
                                // checkoutForm.reset(); 
                            }, { once: true }); 
                        });

                    }, 1500); 
                } else {
                    alert('Mohon lengkapi semua field yang wajib diisi dengan benar.');
                }
            });
            
            // --- Real-time Validation ---
            document.querySelectorAll('input, select, textarea').forEach(field => {
                field.addEventListener('input', function() {
                    if (this.hasAttribute('required') && this.value.trim()) {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // --- Dynamic Info for Bank Transfer ---
            const paymentRadios = document.querySelectorAll('[name="payment_method"]');
            const transferInfoSection = document.createElement('div');
            transferInfoSection.id = 'transferInfo';
            transferInfoSection.className = 'alert alert-info mt-3';
            transferInfoSection.innerHTML = `
                <strong>Informasi Transfer:</strong><br>
                <small>Bank BCA: <strong>1234567890</strong><br>
                A.n: <strong>UMKM Mini-Commerce</strong><br>
                Silakan transfer sesuai total pesanan setelah membuat pesanan.</small>
            `;

            paymentRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const formBody = document.querySelector('#checkoutForm .card-body');
                    const existingInfo = document.getElementById('transferInfo');
                    
                    if (this.value === 'transfer' && !existingInfo) {
                        this.closest('.row.mb-4').insertAdjacentElement('afterend', transferInfoSection);
                    } else if (this.value !== 'transfer' && existingInfo) {
                        existingInfo.remove();
                    }
                });
            });
        });
    </script>
</body>
</html>