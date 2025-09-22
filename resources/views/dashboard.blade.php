
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Buyee</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-pink: #FFB6C1;
            --light-pink: #FFF0F5;
            --medium-pink: #FFC0CB;
            --dark-pink: #FF91A4;
            --soft-pink: #FFCCCB;
            --accent-pink: #FF69B4;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--medium-pink) 50%, var(--soft-pink) 100%);
            box-shadow: 2px 0 15px rgba(255, 182, 193, 0.3);
        }
        
        .sidebar .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 12px 20px;
            border-radius: 12px;
            margin: 4px 0;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.25);
            color: white;
            transform: translateX(8px);
            box-shadow: 0 4px 15px rgba(255,255,255,0.2);
        }
        
        .main-content {
            background: linear-gradient(135deg, var(--light-pink) 0%, #ffffff 100%);
            min-height: 100vh;
        }
        
        .stat-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255, 182, 193, 0.15);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 182, 193, 0.1);
        }
        
        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(255, 182, 193, 0.25);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .table-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(255, 182, 193, 0.15);
            overflow: hidden;
            border: 1px solid rgba(255, 182, 193, 0.1);
        }
        
        .btn-custom {
            border-radius: 12px;
            padding: 10px 20px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .btn-primary {
            background: linear-gradient(45deg, var(--primary-pink), var(--accent-pink));
            border: none;
            color: white;
        }
        
        .btn-primary:hover {
            background: linear-gradient(45deg, var(--accent-pink), var(--dark-pink));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255, 105, 180, 0.3);
        }
        
        .modal-header {
            background: linear-gradient(135deg, var(--primary-pink) 0%, var(--accent-pink) 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        
        .form-control:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(255, 182, 193, 0.25);
        }
        
        .form-select:focus {
            border-color: var(--primary-pink);
            box-shadow: 0 0 0 0.2rem rgba(255, 182, 193, 0.25);
        }
        
        .status-badge {
            font-size: 0.8rem;
            padding: 6px 14px;
            border-radius: 25px;
            font-weight: 500;
        }
        
        .bg-success {
            background: linear-gradient(45deg, #ff9a9e, #fecfef) !important;
            color: #8B5A6B !important;
        }
        
        .bg-warning {
            background: linear-gradient(45deg, #ffeaa7, #fab1a0) !important;
            color: #636e72 !important;
        }
        
        .bg-info {
            background: linear-gradient(45deg, #a8edea, #fed6e3) !important;
            color: #2d3436 !important;
        }
        
        .bg-danger {
            background: linear-gradient(45deg, #fd79a8, #fdcb6e) !important;
            color: #2d3436 !important;
        }
        
        .border-bottom {
            border-color: rgba(255, 182, 193, 0.2) !important;
        }
        
        .bg-light {
            background: var(--light-pink) !important;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(255, 182, 193, 0.1);
        }
        
        .btn-outline-primary {
            color: var(--accent-pink);
            border-color: var(--primary-pink);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-pink);
            border-color: var(--primary-pink);
            color: white;
        }
        
        .btn-outline-danger {
            color: #e17055;
            border-color: #e17055;
        }
        
        .btn-outline-danger:hover {
            background-color: #e17055;
            border-color: #e17055;
        }
        
        .btn-outline-info {
            color: #00b894;
            border-color: #00b894;
        }
        
        .btn-outline-info:hover {
            background-color: #00b894;
            border-color: #00b894;
        }
        
        .btn-outline-success {
            color: var(--accent-pink);
            border-color: var(--medium-pink);
        }
        
        .btn-outline-success:hover {
            background-color: var(--medium-pink);
            border-color: var(--medium-pink);
            color: white;
        }
        
        .page-title {
            color: #8B5A6B;
            font-weight: 600;
        }
        
        .brand-title {
            font-weight: 700;
            font-size: 1.8rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .admin-subtitle {
            font-size: 0.9rem;
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white brand-title"><i class="fas fa-heart me-2"></i> Buyee</h4>
                        <small class="text-white admin-subtitle">Admin Panel</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" onclick="showSection('dashboard')">
                                <i class="fas fa-tachometer-alt me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('products')">
                                <i class="fas fa-box me-2"></i>
                                Kelola Produk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('orders')">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Kelola Pesanan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showSection('categories')">
                                <i class="fas fa-tags me-2"></i>
                                Kategori
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link text-white" href="#" onclick="logout()" style="opacity: 0.8;">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Header -->
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 page-title" id="page-title">Dashboard</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <span class="text-muted">Selamat datang, <strong style="color: var(--accent-pink);">Admin</strong></span>
                    </div>
                </div>

                <!-- Dashboard Section -->
                <div id="dashboard-section">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon" style="background: linear-gradient(45deg, #FFB6C1, #FFC0CB);">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h3 class="mb-0" style="color: #8B5A6B;">150</h3>
                                            <small class="text-muted">Total Produk</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon" style="background: linear-gradient(45deg, #FF69B4, #FFB6C1);">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h3 class="mb-0" style="color: #8B5A6B;">25</h3>
                                            <small class="text-muted">Pesanan Baru</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon" style="background: linear-gradient(45deg, #FFCCCB, #FFB6C1);">
                                            <i class="fas fa-users"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h3 class="mb-0" style="color: #8B5A6B;">1,234</h3>
                                            <small class="text-muted">Total Pengguna</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card border-0">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="stat-icon" style="background: linear-gradient(45deg, #FFC0CB, #FF69B4);">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h3 class="mb-0" style="color: #8B5A6B;">Rp 15M</h3>
                                            <small class="text-muted">Total Penjualan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="table-container">
                        <div class="p-3 border-bottom">
                            <h5 class="mb-0" style="color: #8B5A6B;">Pesanan Terbaru</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID Pesanan</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#001</td>
                                        <td>John Doe</td>
                                        <td>Rp 250,000</td>
                                        <td><span class="badge status-badge bg-warning">Diproses</span></td>
                                        <td>21 Sep 2025</td>
                                    </tr>
                                    <tr>
                                        <td>#002</td>
                                        <td>Jane Smith</td>
                                        <td>Rp 150,000</td>
                                        <td><span class="badge status-badge bg-info">Dikirim</span></td>
                                        <td>21 Sep 2025</td>
                                    </tr>
                                    <tr>
                                        <td>#003</td>
                                        <td>Bob Wilson</td>
                                        <td>Rp 300,000</td>
                                        <td><span class="badge status-badge bg-success">Selesai</span></td>
                                        <td>20 Sep 2025</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Products Section -->
                <div id="products-section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <input type="search" class="form-control me-2" placeholder="Cari produk..." style="width: 300px;">
                            <select class="form-select" style="width: 150px;">
                                <option>Semua Kategori</option>
                                <option>Elektronik</option>
                                <option>Pakaian</option>
                                <option>Makanan</option>
                            </select>
                        </div>
                        <button class="btn btn-primary btn-custom" onclick="openProductModal()">
                            <i class="fas fa-plus me-2"></i>Tambah Produk
                        </button>
                    </div>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>001</td>
                                        <td>Smartphone Samsung</td>
                                        <td>Elektronik</td>
                                        <td>Rp 2,500,000</td>
                                        <td>15</td>
                                        <td><span class="badge bg-success">Aktif</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-custom" onclick="editProduct(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-custom" onclick="deleteProduct(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>002</td>
                                        <td>Kaos Polo</td>
                                        <td>Pakaian</td>
                                        <td>Rp 150,000</td>
                                        <td>30</td>
                                        <td><span class="badge bg-success">Aktif</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-custom" onclick="editProduct(2)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-custom" onclick="deleteProduct(2)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>003</td>
                                        <td>Keripik Singkong</td>
                                        <td>Makanan</td>
                                        <td>Rp 25,000</td>
                                        <td>0</td>
                                        <td><span class="badge bg-danger">Habis</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-custom" onclick="editProduct(3)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-custom" onclick="deleteProduct(3)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Orders Section -->
                <div id="orders-section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex align-items-center">
                            <input type="search" class="form-control me-2" placeholder="Cari pesanan..." style="width: 300px;">
                            <select class="form-select" style="width: 150px;">
                                <option>Semua Status</option>
                                <option>Diproses</option>
                                <option>Dikirim</option>
                                <option>Selesai</option>
                                <option>Batal</option>
                            </select>
                        </div>
                    </div>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Pelanggan</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Alamat</th>
                                        <th>Tanggal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#001</td>
                                        <td>John Doe</td>
                                        <td>Rp 250,000</td>
                                        <td><span class="badge status-badge bg-warning">Diproses</span></td>
                                        <td>Jl. Sudirman No. 123</td>
                                        <td>21 Sep 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info btn-custom" onclick="viewOrderDetail(1)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success btn-custom" onclick="updateOrderStatus(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#002</td>
                                        <td>Jane Smith</td>
                                        <td>Rp 150,000</td>
                                        <td><span class="badge status-badge bg-info">Dikirim</span></td>
                                        <td>Jl. Thamrin No. 456</td>
                                        <td>21 Sep 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info btn-custom" onclick="viewOrderDetail(2)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success btn-custom" onclick="updateOrderStatus(2)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#003</td>
                                        <td>Bob Wilson</td>
                                        <td>Rp 300,000</td>
                                        <td><span class="badge status-badge bg-success">Selesai</span></td>
                                        <td>Jl. Gatot Subroto No. 789</td>
                                        <td>20 Sep 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-info btn-custom" onclick="viewOrderDetail(3)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success btn-custom" onclick="updateOrderStatus(3)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Categories Section -->
                <div id="categories-section" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <input type="search" class="form-control" placeholder="Cari kategori..." style="width: 300px;">
                        </div>
                        <button class="btn btn-primary btn-custom" onclick="openCategoryModal()">
                            <i class="fas fa-plus me-2"></i>Tambah Kategori
                        </button>
                    </div>

                    <div class="table-container">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama Kategori</th>
                                        <th>Jumlah Produk</th>
                                        <th>Tanggal Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>001</td>
                                        <td>Elektronik</td>
                                        <td>45 produk</td>
                                        <td>15 Sep 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-custom" onclick="editCategory(1)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-custom" onclick="deleteCategory(1)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>002</td>
                                        <td>Pakaian</td>
                                        <td>68 produk</td>
                                        <td>15 Sep 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-custom" onclick="editCategory(2)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-custom" onclick="deleteCategory(2)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>003</td>
                                        <td>Makanan</td>
                                        <td>37 produk</td>
                                        <td>15 Sep 2025</td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary btn-custom" onclick="editCategory(3)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger btn-custom" onclick="deleteCategory(3)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalTitle">Tambah Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" id="productName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-select" id="productCategory" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="1">Elektronik</option>
                                    <option value="2">Pakaian</option>
                                    <option value="3">Makanan</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" class="form-control" id="productPrice" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" class="form-control" id="productStock" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="productDescription" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="productStatus">
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveProduct()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Modal -->
    <div class="modal fade" id="orderStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pesanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="orderStatusForm">
                        <div class="mb-3">
                            <label class="form-label">Status Pesanan</label>
                            <select class="form-select" id="orderStatus" required>
                                <option value="diproses">Diproses</option>
                                <option value="dikirim">Dikirim</option>
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control" id="orderNotes" rows="3" placeholder="Tambahkan catatan untuk pelanggan..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveOrderStatus()">Update Status</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 20px; border: none;">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="categoryForm">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="categoryName" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="categoryDescription" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="saveCategory()">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentOrderId = null;
        let currentProductId = null;
        let currentCategoryId = null;

        // Navigation functions
        function showSection(section) {
            // Hide all sections
            const sections = ['dashboard', 'products', 'orders', 'categories'];
            sections.forEach(s => {
                const element = document.getElementById(s + '-section');
                if (element) {
                    element.style.display = 'none';
                }
            });
            
            // Show selected section
            const targetSection = document.getElementById(section + '-section');
            if (targetSection) {
                targetSection.style.display = 'block';
            }
            
            // Update active nav link
            document.querySelectorAll('.sidebar .nav-link').forEach(link => {
                link.classList.remove('active');
            });
            
            // Find the clicked link and make it active
            const clickedLink = document.querySelector(`.sidebar .nav-link[onclick*="${section}"]`);
            if (clickedLink) {
                clickedLink.classList.add('active');
            }
            
            // Update page title
            const titles = {
                'dashboard': 'Dashboard',
                'products': 'Kelola Produk',
                'orders': 'Kelola Pesanan',
                'categories': 'Kategori'
            };
            const titleElement = document.getElementById('page-title');
            if (titleElement) {
                titleElement.textContent = titles[section] || 'Dashboard';
            }
        }

        // Product functions
        function openProductModal(id = null) {
            currentProductId = id;
            const modal = new bootstrap.Modal(document.getElementById('productModal'));
            
            if (id) {
                document.getElementById('productModalTitle').textContent = 'Edit Produk';
                loadProductData(id);
            } else {
                document.getElementById('productModalTitle').textContent = 'Tambah Produk';
                document.getElementById('productForm').reset();
            }
            
            modal.show();
        }

        function loadProductData(id) {
            const sampleData = {
                1: { name: 'Smartphone Samsung', category: '1', price: 2500000, stock: 15, description: 'Smartphone terbaru dengan fitur canggih', status: '1' },
                2: { name: 'Kaos Polo', category: '2', price: 150000, stock: 30, description: 'Kaos polo berkualitas tinggi', status: '1' },
                3: { name: 'Keripik Singkong', category: '3', price: 25000, stock: 0, description: 'Keripik singkong renyah dan gurih', status: '1' }
            };
            
            const data = sampleData[id];
            if (data) {
                document.getElementById('productName').value = data.name;
                document.getElementById('productCategory').value = data.category;
                document.getElementById('productPrice').value = data.price;
                document.getElementById('productStock').value = data.stock;
                document.getElementById('productDescription').value = data.description;
                document.getElementById('productStatus').value = data.status;
            }
        }

        function saveProduct() {
            const form = document.getElementById('productForm');
            if (form.checkValidity()) {
                const formData = {
                    name: document.getElementById('productName').value,
                    category: document.getElementById('productCategory').value,
                    price: document.getElementById('productPrice').value,
                    stock: document.getElementById('productStock').value,
                    description: document.getElementById('productDescription').value,
                    status: document.getElementById('productStatus').value
                };
                
                console.log('Saving product:', formData);
                bootstrap.Modal.getInstance(document.getElementById('productModal')).hide();
                alert(currentProductId ? 'Produk berhasil diupdate!' : 'Produk berhasil ditambahkan!');
            } else {
                form.reportValidity();
            }
        }

        function editProduct(id) {
            openProductModal(id);
        }

        function deleteProduct(id) {
            if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
                console.log('Deleting product:', id);
                alert('Produk berhasil dihapus!');
            }
        }

        // Order functions
        function viewOrderDetail(id) {
            alert('Menampilkan detail pesanan #' + String(id).padStart(3, '0'));
        }

        function updateOrderStatus(id) {
            currentOrderId = id;
            const modal = new bootstrap.Modal(document.getElementById('orderStatusModal'));
            modal.show();
        }

        function saveOrderStatus() {
            const status = document.getElementById('orderStatus').value;
            const notes = document.getElementById('orderNotes').value;
            
            if (status) {
                const formData = {
                    orderId: currentOrderId,
                    status: status,
                    notes: notes
                };
                
                console.log('Updating order status:', formData);
                bootstrap.Modal.getInstance(document.getElementById('orderStatusModal')).hide();
                alert('Status pesanan berhasil diupdate!');
            }
        }

        // Category functions
        function openCategoryModal(id = null) {
            currentCategoryId = id;
            const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
            
            if (id) {
                document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
                loadCategoryData(id);
            } else {
                document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori';
                document.getElementById('categoryForm').reset();
            }
            
            modal.show();
        }

        function loadCategoryData(id) {
            const sampleData = {
                1: { name: 'Elektronik', description: 'Produk elektronik dan gadget' },
                2: { name: 'Pakaian', description: 'Pakaian dan aksesoris fashion' },
                3: { name: 'Makanan', description: 'Makanan dan minuman' }
            };
            
            const data = sampleData[id];
            if (data) {
                document.getElementById('categoryName').value = data.name;
                document.getElementById('categoryDescription').value = data.description;
            }
        }

        function saveCategory() {
            const form = document.getElementById('categoryForm');
            if (form.checkValidity()) {
                const formData = {
                    name: document.getElementById('categoryName').value,
                    description: document.getElementById('categoryDescription').value
                };
                
                console.log('Saving category:', formData);
                bootstrap.Modal.getInstance(document.getElementById('categoryModal')).hide();
                alert(currentCategoryId ? 'Kategori berhasil diupdate!' : 'Kategori berhasil ditambahkan!');
            } else {
                form.reportValidity();
            }
        }

        function editCategory(id) {
            openCategoryModal(id);
        }

        function deleteCategory(id) {
            if (confirm('Apakah Anda yakin ingin menghapus kategori ini? Semua produk dalam kategori ini akan terpengaruh.')) {
                console.log('Deleting category:', id);
                alert('Kategori berhasil dihapus!');
            }
        }

        // Logout function
        function logout() {
            if (confirm('Apakah Anda yakin ingin keluar?')) {
                alert('Logout berhasil!');
                // In real implementation: window.location.href = 'login.php';
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial active state
            showSection('dashboard');
            
            // Add search functionality
            const searchInputs = document.querySelectorAll('input[type="search"]');
            searchInputs.forEach(input => {
                input.addEventListener('input', function() {
                    console.log('Searching for:', this.value);
                });
            });
            
            // Add filter functionality
            const filterSelects = document.querySelectorAll('select.form-select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    console.log('Filtering by:', this.value);
                });
            });
        });

        // Helper functions
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }

        function formatDate(date) {
            return new Date(date).toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    </script>
</body>
</html>