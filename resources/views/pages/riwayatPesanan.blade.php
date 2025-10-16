
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Buyee</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #f8a9c2;
            --secondary-color: #f5459dff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --info-color: #17a2b8;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #ffe4edff 100%);
            font-family: 'Inter', sans-serif;
            padding-bottom: 2rem;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .page-header {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .order-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .order-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
        }

        .order-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-diproses {
            background: linear-gradient(135deg, var(--info-color) 0%, #138496 100%);
            color: white;
        }

        .status-dikirim {
            background: linear-gradient(135deg, var(--warning-color) 0%, #e0a800 100%);
            color: white;
        }

        .status-selesai {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            color: white;
        }

        .status-batal {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%);
            color: white;
        }

        .order-timeline {
            position: relative;
            padding: 1rem 0;
        }

        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            position: relative;
        }

        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1rem;
            position: relative;
            z-index: 2;
        }

        .timeline-icon.active {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            color: white;
        }

        .timeline-icon.current {
            background: linear-gradient(135deg, var(--info-color) 0%, #138496 100%);
            color: white;
            animation: pulse 2s infinite;
        }

        .timeline-icon.pending {
            background: #e9ecef;
            color: #6c757d;
        }

        .timeline-line {
            position: absolute;
            left: 20px;
            top: 40px;
            width: 2px;
            height: calc(100% - 40px);
            background: #e9ecef;
            z-index: 1;
        }

        .timeline-item:last-child .timeline-line {
            display: none;
        }

        .product-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .product-image {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #dee2e6, #adb5bd);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #6c757d;
            font-size: 1.5rem;
        }

        .order-actions {
            padding: 1.5rem;
            background: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }

        .btn-action {
            border-radius: 25px;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(248, 169, 194, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border: none;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        }

        .btn-danger {
            background: linear-gradient(135deg, var(--danger-color) 0%, #c82333 100%);
            border: none;
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }

        .filter-tabs {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .nav-tabs {
            border-bottom: none;
        }

        .nav-tabs .nav-link {
            border: none;
            padding: 1rem 2rem;
            color: #6c757d;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            background: #f8f9fa;
        }

        .nav-tabs .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-color: transparent;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .empty-state i {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 1rem;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .order-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .order-card:nth-child(1) { animation-delay: 0.1s; }
        .order-card:nth-child(2) { animation-delay: 0.2s; }
        .order-card:nth-child(3) { animation-delay: 0.3s; }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }

        .tracking-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
        }
    </style>
</head>
<body>
    
    <nav class="pt-2 text-sm text-gray-500 navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-store me-2"></i>
                Buyee
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/pencarian">
                            <i class="fas fa-search me-1"></i>Pencarian
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Keranjang <span class="badge bg-warning">2</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>John Doe
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                            <li><a class="dropdown-item" href="status_pesanan.html">Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="fas fa-box text-primary me-3"></i>
                        Status Pesanan Saya
                    </h2>
                    <p class="text-muted mb-0">Pantau perkembangan pesanan Anda di sini</p>
                </div>
                <div class="text-end">
                    <div class="badge bg-light text-dark fs-6 px-3 py-2">
                        Total Pesanan: <strong>{{ $ordersData->count() }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="filter-tabs">
            <ul class="nav nav-tabs" id="orderTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab">
                        <i class="fas fa-list me-2"></i>Semua ({{ $ordersData->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="process-tab" data-bs-toggle="tab" data-bs-target="#process" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>Diproses ({{ $ordersData->where('status', 'diproses')->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="shipped-tab" data-bs-toggle="tab" data-bs-target="#shipped" type="button" role="tab">
                        <i class="fas fa-shipping-fast me-2"></i>Dikirim ({{ $ordersData->where('status', 'dikirim')->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab">
                        <i class="fas fa-check-circle me-2"></i>Selesai ({{ $ordersData->where('status', 'selesai')->count() }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab">
                        <i class="fas fa-times-circle me-2"></i>Dibatalkan (1)
                    </button>
                </li>
            </ul>
        </div>

        <div class="tab-content" id="orderTabContent">
            <div class="tab-pane fade show active" id="all" role="tabpanel">
                <div class="card order-card">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Order #ORD-2025-001</h6>
                                <small class="text-muted">Dipesan pada: 20 September 2025, 14:30 WIB</small>
                            </div>
                            <span class="status-badge status-dikirim">
                                <i class="fas fa-shipping-fast me-1"></i>Dikirim
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="order-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon active">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Pesanan Dibuat</strong>
                                    <br><small class="text-muted">20 Sep 2025, 14:30 WIB</small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon active">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Sedang Diproses</strong>
                                    <br><small class="text-muted">20 Sep 2025, 15:45 WIB</small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon current">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Sedang Dikirim</strong>
                                    <br><small class="text-muted">21 Sep 2025, 09:15 WIB</small>
                                    <div class="tracking-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>JNE REG - 12345678901234</strong>
                                        <br><small>Paket dalam perjalanan menuju alamat tujuan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon pending">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div>
                                    <strong>Pesanan Selesai</strong>
                                    <br><small class="text-muted">Menunggu konfirmasi penerimaan</small>
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-4 mb-3">Produk yang Dipesan:</h6>
                        <div class="product-item">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Kaos Polos Premium Cotton</h6>
                                <p class="text-muted mb-1">2x Rp 75.000</p>
                                <p class="mb-0"><strong>Subtotal: Rp 150.000</strong></p>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal Produk:</span>
                                <span>Rp 150.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkos Kirim:</span>
                                <span>Rp 15.000</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran:</strong>
                                <strong class="text-success">Rp 165.000</strong>
                            </div>
                        </div>
                    </div>

                    <div class="order-actions">
                        <button class="btn btn-primary btn-action" onclick="trackOrder('ORD-2025-001')">
                            <i class="fas fa-search me-2"></i>Lacak Pesanan
                        </button>
                        <button class="btn btn-success btn-action" onclick="confirmDelivery('ORD-2025-001')">
                            <i class="fas fa-check me-2"></i>Konfirmasi Diterima
                        </button>
                        <button class="btn btn-outline-secondary btn-action" onclick="contactSeller('ORD-2025-001')">
                            <i class="fas fa-comments me-2"></i>Hubungi Penjual
                        </button>
                    </div>
                </div>

                <div class="card order-card">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Order #ORD-2025-002</h6>
                                <small class="text-muted">Dipesan pada: 22 September 2025, 10:15 WIB</small>
                            </div>
                            <span class="status-badge status-diproses">
                                <i class="fas fa-clock me-1"></i>Diproses
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="order-timeline">
                            <div class="timeline-item">
                                <div class="timeline-icon active">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Pesanan Dibuat</strong>
                                    <br><small class="text-muted">22 Sep 2025, 10:15 WIB</small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon current">
                                    <i class="fas fa-cogs"></i>
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Sedang Diproses</strong>
                                    <br><small class="text-muted">22 Sep 2025, 11:30 WIB</small>
                                    <div class="tracking-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Pesanan sedang disiapkan oleh penjual
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon pending">
                                    <i class="fas fa-truck"></i>
                                </div>
                                <div class="timeline-line"></div>
                                <div>
                                    <strong>Dikirim</strong>
                                    <br><small class="text-muted">Menunggu proses selanjutnya</small>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-icon pending">
                                    <i class="fas fa-home"></i>
                                </div>
                                <div>
                                    <strong>Pesanan Selesai</strong>
                                    <br><small class="text-muted">Menunggu proses selanjutnya</small>
                                </div>
                            </div>
                        </div>

                        <h6 class="mt-4 mb-3">Produk yang Dipesan:</h6>
                        <div class="product-item">
                            <div class="product-image">
                                <i class="fas fa-running"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Sepatu Sneakers Premium</h6>
                                <p class="text-muted mb-1">1x Rp 250.000</p>
                                <p class="mb-0"><strong>Subtotal: Rp 250.000</strong></p>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal Produk:</span>
                                <span>Rp 250.000</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkos Kirim:</span>
                                <span>Rp 20.000</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran:</strong>
                                <strong class="text-success">Rp 270.000</strong>
                            </div>
                        </div>
                    </div>

                    <div class="order-actions">
                        <button class="btn btn-danger btn-action" onclick="cancelOrder('ORD-2025-002')">
                            <i class="fas fa-times me-2"></i>Batalkan Pesanan
                        </button>
                        <button class="btn btn-outline-secondary btn-action" onclick="contactSeller('ORD-2025-002')">
                            <i class="fas fa-comments me-2"></i>Hubungi Penjual
                        </button>
                    </div>
                </div>

                <div class="card order-card">
                    <div class="order-header">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">Order #ORD-2025-003</h6>
                                <small class="text-muted">Dipesan pada: 15 September 2025, 16:20 WIB</small>
                            </div>
                            <span class="status-badge status-selesai">
                                <i class="fas fa-check-circle me-1"></i>Selesai
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <div class="alert alert-success mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            Pesanan telah selesai dan diterima pada <strong>18 Sep 2025, 14:30 WIB</strong>
                        </div>

                        <h6 class="mb-3">Produk yang Dipesan:</h6>
                        <div class="product-item">
                            <div class="product-image">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">Case HP Anti-Crack</h6>
                                <p class="text-muted mb-1">3x Rp 25.000</p>
                                <p class="mb-0"><strong>Subtotal: Rp 75.000</strong></p>
                            </div>
                        </div>

                        <div class="mt-3 p-3 bg-light rounded">
                            <div class="d-flex justify-content-between">
                                <strong>Total Pembayaran:</strong>
                                <strong class="text-success">Rp 90.000</strong>
                            </div>
                        </div>
                    </div>

                    <div class="order-actions">
                        <button class="btn btn-primary btn-action" onclick="reorder('ORD-2025-003')">
                            <i class="fas fa-redo me-2"></i>Pesan Lagi
                        </button>
                        <button class="btn btn-outline-warning btn-action" onclick="reviewProduct('ORD-2025-003')">
                            <i class="fas fa-star me-2"></i>Beri Ulasan
                        </button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="process" role="tabpanel">
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <h4>Pesanan Sedang Diproses</h4>
                    <p class="text-muted">Anda memiliki 2 pesanan yang sedang diproses</p>
                </div>
            </div>

            <div class="tab-pane fade" id="shipped" role="tabpanel">
                <div class="empty-state">
                    <i class="fas fa-shipping-fast"></i>
                    <h4>Pesanan Sedang Dikirim</h4>
                    <p class="text-muted">Anda memiliki 1 pesanan yang sedang dalam perjalanan</p>
                </div>
            </div>

            <div class="tab-pane fade" id="completed" role="tabpanel">
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h4>Pesanan Selesai</h4>
                    <p class="text-muted">Anda memiliki 1 pesanan yang telah selesai</p>
                </div>
            </div>

            <div class="tab-pane fade" id="cancelled" role="tabpanel">
                <div class="empty-state">
                    <i class="fas fa-times-circle"></i>
                    <h4>Pesanan Dibatalkan</h4>
                    <p class="text-muted">Anda memiliki 1 pesanan yang dibatalkan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trackingModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Lacak Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <h6 id="trackingOrderId">Order #ORD-2025-001</h6>
                        <p class="text-muted">Informasi pengiriman terkini</p>
                    </div>
                    
                    <div class="tracking-timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon active">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="timeline-line"></div>
                            <div>
                                <strong>Paket Diterima Kurir</strong>
                                <br><small class="text-muted">21 Sep 2025, 09:15 WIB - Jakarta Pusat</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon active">
                                <i class="fas fa-truck"></i>
                            </div>
                            <div class="timeline-line"></div>
                            <div>
                                <strong>Dalam Perjalanan</strong>
                                <br><small class="text-muted">21 Sep 2025, 15:30 WIB - Sortir Jakarta</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon current">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="timeline-line"></div>
                            <div>
                                <strong>Transit</strong>
                                <br><small class="text-muted">22 Sep 2025, 08:00 WIB - Rembangan</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-icon pending">
                                <i class="fas fa-home"></i>
                            </div>
                            <div>
                                <strong>Akan Diantarkan</strong>
                                <br><small class="text-muted">Estimasi: 22 Sep 2025, 16:00 WIB</small>
                            </div>
                        </div>
                    </div>

                    <div class="tracking-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Resi: 12345678901234 (JNE REG)</strong>
                        <br><small>Hubungi kurir: 0812-3456-7890</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="openTrackingWebsite()">
                        <i class="fas fa-external-link-alt me-2"></i>Lihat di Website JNE
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Batalkan Pesanan
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin membatalkan pesanan <strong id="cancelOrderId">ORD-2025-002</strong>?</p>
                    
                    <div class="mb-3">
                        <label class="form-label">Alasan pembatalan:</label>
                        <select class="form-select" id="cancelReason">
                            <option value="">Pilih alasan...</option>
                            <option value="change_mind">Berubah pikiran</option>
                            <option value="wrong_item">Salah pilih produk</option>
                            <option value="found_cheaper">Menemukan harga lebih murah</option>
                            <option value="delivery_time">Pengiriman terlalu lama</option>
                            <option value="other">Lainnya</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan tambahan (opsional):</label>
                        <textarea class="form-control" id="cancelNote" rows="3" placeholder="Berikan keterangan tambahan..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        Pembatalan pesanan akan diproses dalam 1x24 jam. Dana akan dikembalikan sesuai metode pembayaran yang digunakan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                        <i class="fas fa-times me-2"></i>Ya, Batalkan Pesanan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-star me-2"></i>
                        Beri Ulasan Produk
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="product-image mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h6>Case HP Anti-Crack</h6>
                        <p class="text-muted">Order #ORD-2025-003</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Rating Produk:</label>
                        <div class="rating-stars text-center">
                            <i class="fas fa-star star-rating" data-rating="1"></i>
                            <i class="fas fa-star star-rating" data-rating="2"></i>
                            <i class="fas fa-star star-rating" data-rating="3"></i>
                            <i class="fas fa-star star-rating" data-rating="4"></i>
                            <i class="fas fa-star star-rating" data-rating="5"></i>
                        </div>
                        <div class="text-center mt-2">
                            <span id="ratingText" class="text-muted">Pilih rating</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ulasan Anda:</label>
                        <textarea class="form-control" id="reviewText" rows="4" placeholder="Bagikan pengalaman Anda dengan produk ini..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Upload Foto (opsional):</label>
                        <input type="file" class="form-control" id="reviewPhoto" accept="image/*" multiple>
                        <small class="text-muted">Maksimal 5 foto, ukuran 2MB per foto</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitReview()">
                        <i class="fas fa-paper-plane me-2"></i>Kirim Ulasan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sample data for orders
        let currentOrderId = null;
        let selectedRating = 0;

        // Track order function
        function trackOrder(orderId) {
            currentOrderId = orderId;
            document.getElementById('trackingOrderId').textContent = `Order #${orderId}`;
            
            // Show tracking modal
            const trackingModal = new bootstrap.Modal(document.getElementById('trackingModal'));
            trackingModal.show();
        }

        // Cancel order function
        function cancelOrder(orderId) {
            currentOrderId = orderId;
            document.getElementById('cancelOrderId').textContent = orderId;
            
            // Show cancel modal
            const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
            cancelModal.show();
        }

        // Confirm cancel function
        function confirmCancel() {
            const reason = document.getElementById('cancelReason').value;
            const note = document.getElementById('cancelNote').value;
            
            if (!reason) {
                alert('Mohon pilih alasan pembatalan!');
                return;
            }
            
            // Simulate API call
            showNotification('Permintaan pembatalan sedang diproses...', 'info');
            
            setTimeout(() => {
                showNotification('Pesanan berhasil dibatalkan!', 'success');
                
                // Update order status in UI
                updateOrderStatus(currentOrderId, 'batal');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('cancelModal')).hide();
            }, 2000);
        }

        // Confirm delivery function
        function confirmDelivery(orderId) {
            if (confirm('Apakah Anda yakin telah menerima pesanan ini?')) {
                showNotification('Pesanan dikonfirmasi diterima!', 'success');
                updateOrderStatus(orderId, 'selesai');
            }
        }

        // Contact seller function
        function contactSeller(orderId) {
            showNotification('Menghubungkan ke chat penjual...', 'info');
            // In real implementation, this would open chat or redirect to messaging
            setTimeout(() => {
                alert('Fitur chat akan tersedia setelah integrasi dengan sistem messaging.');
            }, 1000);
        }

        // Reorder function
        function reorder(orderId) {
            if (confirm('Tambahkan produk dari pesanan ini ke keranjang?')) {
                showNotification('Produk berhasil ditambahkan ke keranjang!', 'success');
                
                // Update cart badge
                const badge = document.querySelector('.navbar .badge');
                if (badge) {
                    let count = parseInt(badge.textContent) || 0;
                    badge.textContent = count + 3; // Assuming 3 items from the order
                }
            }
        }

        // Review product function
        function reviewProduct(orderId) {
            currentOrderId = orderId;
            
            // Reset review form
            selectedRating = 0;
            document.getElementById('reviewText').value = '';
            document.getElementById('reviewPhoto').value = '';
            updateStarDisplay();
            
            // Show review modal
            const reviewModal = new bootstrap.Modal(document.getElementById('reviewModal'));
            reviewModal.show();
        }

        // Star rating functionality
        document.querySelectorAll('.star-rating').forEach(star => {
            star.addEventListener('click', function() {
                selectedRating = parseInt(this.getAttribute('data-rating'));
                updateStarDisplay();
            });
            
            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.getAttribute('data-rating'));
                highlightStars(rating);
            });
        });

        document.querySelector('.rating-stars').addEventListener('mouseleave', function() {
            updateStarDisplay();
        });

        function highlightStars(rating) {
            document.querySelectorAll('.star-rating').forEach((star, index) => {
                if (index < rating) {
                    star.style.color = '#ffc107';
                } else {
                    star.style.color = '#dee2e6';
                }
            });
            
            const ratingTexts = ['', 'Sangat Buruk', 'Buruk', 'Biasa', 'Bagus', 'Sangat Bagus'];
            document.getElementById('ratingText').textContent = ratingTexts[rating] || 'Pilih rating';
        }

        function updateStarDisplay() {
            highlightStars(selectedRating);
        }

        // Submit review function
        function submitReview() {
            if (selectedRating === 0) {
                alert('Mohon berikan rating untuk produk!');
                return;
            }
            
            const reviewText = document.getElementById('reviewText').value;
            if (!reviewText.trim()) {
                alert('Mohon tulis ulasan Anda!');
                return;
            }
            
            // Simulate API call
            showNotification('Mengirim ulasan...', 'info');
            
            setTimeout(() => {
                showNotification('Ulasan berhasil dikirim! Terima kasih atas feedback Anda.', 'success');
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('reviewModal')).hide();
            }, 2000);
        }

        // Update order status in UI
        function updateOrderStatus(orderId, newStatus) {
            const orderCard = document.querySelector(`[onclick*="${orderId}"]`)?.closest('.order-card');
            if (orderCard) {
                const statusBadge = orderCard.querySelector('.status-badge');
                const statusClasses = {
                    'diproses': 'status-diproses',
                    'dikirim': 'status-dikirim',
                    'selesai': 'status-selesai',
                    'batal': 'status-batal'
                };
                
                const statusTexts = {
                    'diproses': '<i class="fas fa-clock me-1"></i>Diproses',
                    'dikirim': '<i class="fas fa-shipping-fast me-1"></i>Dikirim',
                    'selesai': '<i class="fas fa-check-circle me-1"></i>Selesai',
                    'batal': '<i class="fas fa-times-circle me-1"></i>Dibatalkan'
                };
                
                // Remove old status class
                statusBadge.className = 'status-badge ' + statusClasses[newStatus];
                statusBadge.innerHTML = statusTexts[newStatus];
            }
        }

        // Open tracking website
        function openTrackingWebsite() {
            window.open('https://www.jne.co.id/id/tracking/trace', '_blank');
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            // Remove existing notifications
            const existingNotifs = document.querySelectorAll('.custom-notification');
            existingNotifs.forEach(notif => notif.remove());
            
            // Create notification
            const notification = document.createElement('div');
            notification.className = `custom-notification alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'info' ? 'info-circle' : 'exclamation-triangle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 4 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 4000);
        }

        // Tab filtering functionality
        document.querySelectorAll('#orderTabs button').forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-bs-target');
                
                // For demo purposes, show appropriate message for each tab
                if (targetTab !== '#all') {
                    setTimeout(() => {
                        showNotification('Filter berhasil diterapkan!', 'info');
                    }, 300);
                }
            });
        });

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Add some interactive effects
            document.querySelectorAll('.order-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-5px) scale(1.02)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
            
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + R for refresh
            if (e.altKey && e.key === 'r') {
                e.preventDefault();
                location.reload();
            }
            
            // Escape to close modals
            if (e.key === 'Escape') {
                const modals = document.querySelectorAll('.modal.show');
                modals.forEach(modal => {
                    bootstrap.Modal.getInstance(modal)?.hide();
                });
            }
        });

        // Auto refresh order status every 5 minutes
        setInterval(() => {
            console.log('Auto refreshing order status...');
            // In real implementation, this would fetch latest order status from server
        }, 300000);
    </script>
</body>
</html>
