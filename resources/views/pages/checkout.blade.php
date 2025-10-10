<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - UMKM Mini-Commerce</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .checkout-container {
            background: #fff0f5;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .checkout-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
        }
        
        .section-header {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .cart-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e9ecef;
        }
        
        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
        }
        
        .summary-card {
            background: linear-gradient(135deg, #f5459dff 0%, #f8a9c2 100%);
            color: white;
            border-radius: 12px;
        }
        
        .btn-checkout {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            padding: 0.8rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-checkout:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .alert-info {
            background: linear-gradient(135deg, #f8a9c2 0%, #f5459dff 100%);
            border: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="checkout-container">
        <div class="container">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex align-items-center mb-3">
                        <a href="cart.php" class="btn btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <h2 class="mb-0">
                            <i class="fas fa-credit-card text-primary me-2"></i>
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
                <!-- Form Checkout -->
                <div class="col-lg-8">
                    <div class="card checkout-card mb-4">
                        <div class="card-body p-4">
                            <form id="checkoutForm" method="POST" action="process_checkout.php">
                                <!-- Data Pelanggan -->
                                <h5 class="section-header">
                                    <i class="fas fa-user text-primary me-2"></i>
                                    Data Pelanggan
                                </h5>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nama Lengkap *</label>
                                        <input type="text" class="form-control" name="customer_name" 
                                               value="John Doe" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email *</label>
                                        <input type="email" class="form-control" name="customer_email" 
                                               value="john@example.com" required>
                                    </div>
                                </div>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label">Nomor Telepon *</label>
                                        <input type="tel" class="form-control" name="customer_phone" 
                                               placeholder="08xxxxxxxxxx" required>
                                    </div>
                                </div>

                                <!-- Alamat Pengiriman -->
                                <h5 class="section-header">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                    Alamat Pengiriman
                                </h5>
                                
                                <div class="mb-3">
                                    <label class="form-label">Alamat Lengkap *</label>
                                    <textarea class="form-control" name="shipping_address" rows="3" 
                                              placeholder="Jalan, Nomor Rumah, RT/RW" required></textarea>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label">Kota *</label>
                                        <input type="text" class="form-control" name="city" 
                                               placeholder="Nama Kota" required>
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
                                        <input type="text" class="form-control" name="postal_code" 
                                               placeholder="12345" maxlength="5" required>
                                    </div>
                                </div>

                                <!-- Metode Pembayaran -->
                                <h5 class="section-header">
                                    <i class="fas fa-money-check text-warning me-2"></i>
                                    Metode Pembayaran
                                </h5>
                                
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   id="cod" value="cod" checked>
                                            <label class="form-check-label" for="cod">
                                                <i class="fas fa-truck text-success me-2"></i>
                                                <strong>Cash On Delivery (COD)</strong>
                                                <br><small class="text-muted">Bayar saat barang diterima</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check p-3 border rounded">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   id="transfer" value="transfer">
                                            <label class="form-check-label" for="transfer">
                                                <i class="fas fa-university text-primary me-2"></i>
                                                <strong>Transfer Bank</strong>
                                                <br><small class="text-muted">Transfer ke rekening toko</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Catatan -->
                                <h5 class="section-header">
                                    <i class="fas fa-sticky-note text-info me-2"></i>
                                    Catatan Pesanan
                                </h5>
                                
                                <div class="mb-3">
                                    <textarea class="form-control" name="order_notes" rows="3" 
                                              placeholder="Catatan tambahan untuk penjual (opsional)"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ringkasan Pesanan -->
                <div class="col-lg-4">
                    <div class="card checkout-card">
                        <div class="card-body summary-card p-4">
                            <h5 class="section-header text-white border-bottom border-light">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Ringkasan Pesanan
                            </h5>
                            
                            <!-- Items -->
                            <div class="cart-items mb-4">
                                <div class="cart-item bg-white text-dark">
                                    <div class="d-flex align-items-center">
                                        <div class="product-image me-3">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Kaos Polos Premium</h6>
                                            <small class="text-muted">Kategori: Fashion</small>
                                            <div class="d-flex justify-content-between mt-1">
                                                <span>2 x Rp 75.000</span>
                                                <strong>Rp 150.000</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="cart-item bg-white text-dark">
                                    <div class="d-flex align-items-center">
                                        <div class="product-image me-3">
                                            <i class="fas fa-image"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">Sepatu Sneakers</h6>
                                            <small class="text-muted">Kategori: Footwear</small>
                                            <div class="d-flex justify-content-between mt-1">
                                                <span>1 x Rp 250.000</span>
                                                <strong>Rp 250.000</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Total -->
                            <div class="border-top border-light pt-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp 400.000</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkos Kirim:</span>
                                    <span>Rp 15.000</span>
                                </div>
                                <hr class="border-light">
                                <div class="d-flex justify-content-between mb-4">
                                    <strong class="h5">Total:</strong>
                                    <strong class="h5">Rp 415.000</strong>
                                </div>
                                
                                <button type="submit" form="checkoutForm" class="btn btn-checkout btn-success w-100 btn-lg">
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
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
            if (!phoneRegex.test(phone.value)) {
                phone.classList.add('is-invalid');
                isValid = false;
            }
            
            // Postal code validation
            const postalCode = document.querySelector('[name="postal_code"]');
            const postalRegex = /^[0-9]{5}$/;
            if (!postalRegex.test(postalCode.value)) {
                postalCode.classList.add('is-invalid');
                isValid = false;
            }
            
            if (isValid) {
                // Show loading state
                const submitBtn = this.querySelector('[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                
                // Simulate processing
                setTimeout(() => {
                    alert('Pesanan berhasil dibuat!\n\nPesanan Anda sedang diproses. Anda akan menerima konfirmasi melalui email.');
                    
                    // Reset form atau redirect
                    // window.location.href = 'order_success.php';
                    
                    // Reset button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 2000);
            } else {
                alert('Mohon lengkapi semua field yang wajib diisi dengan benar.');
            }
        });
        
        // Real-time validation
        document.querySelectorAll('input, select, textarea').forEach(field => {
            field.addEventListener('input', function() {
                if (this.hasAttribute('required') && this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });
        
        // Format currency display
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }
        
        // Payment method change handler
        document.querySelectorAll('[name="payment_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const transferInfo = document.getElementById('transferInfo');
                if (this.value === 'transfer' && !transferInfo) {
                    // Add transfer info
                    const info = document.createElement('div');
                    info.id = 'transferInfo';
                    info.className = 'alert alert-info mt-3';
                    info.innerHTML = `
                        <strong>Informasi Transfer:</strong><br>
                        Bank BCA: 1234567890<br>
                        A.n: UMKM Mini-Commerce<br>
                        <small>Silakan transfer sesuai total pesanan</small>
                    `;
                    this.closest('.card-body').appendChild(info);
                } else if (this.value === 'cod' && transferInfo) {
                    transferInfo.remove();
                }
            });
        });
    </script>
</body>
</html>