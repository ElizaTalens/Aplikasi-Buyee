<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - Buyee</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
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

body {
    background-color: var(--light-pink);
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
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-3">
                <div class="text-center mb-4">
                    <h4 class="text-white brand-title"><i class="fas fa-heart me-2"></i> Buyee</h4>
                    <small class="text-white admin-subtitle">Admin Panel</small>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="#" onclick="showSection('dashboard')">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('products')">
                            <i class="fas fa-box me-2"></i> Kelola Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('orders')">
                            <i class="fas fa-shopping-cart me-2"></i> Kelola Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="showSection('categories')">
                            <i class="fas fa-tags me-2"></i> Kategori
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-white" href="#" onclick="logout()" style="opacity: 0.8;">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2 page-title" id="page-title">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <span class="text-muted">Selamat datang, <strong style="color: var(--accent-pink);">Admin</strong></span>
                </div>
            </div>
            <div id="dashboard-section">
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card border-0">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: linear-gradient(45deg, #FFB6C1, #FFC0CB);">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="products">...</h3>
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
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="orders">...</h3>
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
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="users">...</h3>
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
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="sales">...</h3>
                                        <small class="text-muted">Total Penjualan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                            <tbody id="dashboard-orders-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="products-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="search" class="form-control me-2" placeholder="Cari produk..." style="width: 300px;">
                        <select class="form-select" style="width: 150px;" id="productFilterCategory">
                            <option value="">Semua Kategori</option>
                        </select>
                    </div>
                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#productModal" onclick="openProductModal()">
                        <i class="fas fa-plus me-2"></i>Tambah Produk
                    </button>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Foto</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
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
                            <tbody id="orders-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="categories-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <input type="search" class="form-control" placeholder="Cari kategori..." style="width: 300px;">
                    </div>
                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="openCategoryModal()">
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
                            <tbody id="categories-table-body">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<div class="modal fade" id="productModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalTitle">Tambah Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="productForm" enctype="multipart/form-data">
                    <div class="row">
                        <input type="hidden" id="product-id" name="id">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" id="productCategory" name="category_id" required>
                                <option value="">Pilih Kategori</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" class="form-control" id="productPrice" name="price" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" class="form-control" id="productStock" name="stock" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Foto Produk (Max 2MB)</label>
                        <input type="file" class="form-control" id="productImageFile" name="image_file">
                        <small class="text-muted mt-2">Kosongkan jika tidak ingin mengubah foto.</small>
                        <div id="image-preview" class="mt-2"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="productStatus" name="is_active">
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
<div class="modal fade" id="orderStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Pesanan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="orderStatusForm">
                    <input type="hidden" id="order-id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Status Pesanan</label>
                        <select class="form-select" id="orderStatus" name="status" required>
                            <option value="diproses">Diproses</option>
                            <option value="dikirim">Dikirim</option>
                            <option value="selesai">Selesai</option>
                            <option value="batal">Batal</option>
                        </select>
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
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="category-id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="saveCategory()" id="saveCategoryBtn">Simpan</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
let currentProductId = null;
let currentOrderId = null;
let currentCategoryId = null;

const formatCurrency = (amount) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
const formatDate = (dateString) => new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
const getStatusBadge = (status) => {
    const statuses = {
        'diproses': 'bg-warning',
        'dikirim': 'bg-info',
        'selesai': 'bg-success',
        'batal': 'bg-danger'
    };
    return `<span class="badge status-badge ${statuses[status] || 'bg-secondary'}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
}

function closeAllModalsAndCleanUp() {
    const modals = document.querySelectorAll('.modal.show');
    modals.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    });
    const backdrops = document.querySelectorAll('.modal-backdrop');
    backdrops.forEach(backdrop => {
        backdrop.remove();
    });
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
}

function showSection(section) {
    document.querySelectorAll('.main-content > div[id$="-section"]').forEach(s => s.style.display = 'none');
    document.getElementById(`${section}-section`).style.display = 'block';
    document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
    document.querySelector(`.sidebar .nav-link[onclick*="${section}"]`).classList.add('active');
    const titles = {
        'dashboard': 'Dashboard',
        'products': 'Kelola Produk',
        'orders': 'Kelola Pesanan',
        'categories': 'Kategori'
    };
    document.getElementById('page-title').textContent = titles[section];
    if (section === 'dashboard') loadDashboardStats();
    if (section === 'products') {
        loadProducts(); // Panggil tanpa filter awal
        loadCategoriesForProductSection(); // Memuat kategori untuk dropdown filter
    }
    if (section === 'orders') loadOrders();
    if (section === 'categories') loadCategories();
}

async function loadDashboardStats() {
    try {
        const response = await fetch('/buyee-admin/dashboard/stats');
        const data = await response.json();
        document.querySelector('#dashboard-section h3[data-stat="products"]').textContent = data.total_products;
        document.querySelector('#dashboard-section h3[data-stat="orders"]').textContent = data.new_orders;
        document.querySelector('#dashboard-section h3[data-stat="users"]').textContent = data.total_users;
        document.querySelector('#dashboard-section h3[data-stat="sales"]').textContent = formatCurrency(data.total_sales.replace(/\./g, ''));
    } catch (error) {
        console.error('Gagal memuat statistik:', error);
    }
}

// ** FUNGSI UNTUK MENGISI DROPDOWN FILTER KATEGORI **
async function loadCategoriesForProductSection() {
    try {
        const response = await fetch('/buyee-admin/categories');
        const categories = await response.json();
        const select = document.querySelector('#productFilterCategory');
        
        select.innerHTML = ''; // Kosongkan
        select.innerHTML += '<option value="">Semua Kategori</option>'; // Default option
        
        categories.forEach(c => select.innerHTML += `<option value="${c.id}">${c.name}</option>`);
        
    } catch (error) {
        console.error('Gagal memuat kategori untuk filter:', error);
    }
}


// ** FUNGSI UTAMA: LOAD PRODUK DENGAN FILTER **
async function loadProducts(query = '', categoryId = '') {
    try {
        // Bangun URL dengan parameter filter
        let url = '/buyee-admin/products';
        const params = [];
        
        if (query) {
            params.push(`query=${encodeURIComponent(query)}`);
        }
        if (categoryId) {
            params.push(`category_id=${categoryId}`);
        }
        
        if (params.length > 0) {
            url += '?' + params.join('&');
        }

        const response = await fetch(url);
        const products = await response.json();
        const tbody = document.querySelector('#products-table-body');
        tbody.innerHTML = '';
        
        if (products.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td></tr>`;
            return;
        }

        products.forEach(p => {
            const statusText = p.stock > 0 && p.is_active ? 'Aktif' : 'Habis/Nonaktif';
            const statusBadgeClass = p.stock > 0 && p.is_active ? 'bg-success' : 'bg-danger';
            
            // Logika Image: Menggunakan path relatif database
            const imageUrl = p.image 
                ? `/${p.image}` // Akses dari root public: /uploads/products/namafile.jpg
                : 'https://via.placeholder.com/50x50?text=No+Img';
            
            const imageCell = `
                <td>
                    <img 
                        src="${imageUrl}" 
                        alt="${p.name}" 
                        style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;"
                        onerror="this.onerror=null;this.src='https://via.placeholder.com/50x50?text=No+Img';"
                    >
                </td>`;

            tbody.innerHTML += `
                <tr>
                    <td>${String(p.id).padStart(3, '0')}</td>
                    ${imageCell}
                    <td>${p.name}</td>
                    <td>${p.category ? p.category.name : 'N/A'}</td>
                    <td>${formatCurrency(p.price)}</td>
                    <td>${p.stock}</td>
                    <td><span class="badge ${statusBadgeClass}">${statusText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editProduct(${p.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${p.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
        });
    } catch (error) {
        console.error('Gagal memuat produk:', error);
    }
}


// ** FUNGSI UNTUK MENYARING (SEARCH & FILTER) **
function filterProducts() {
    const query = document.querySelector('#products-section input[type="search"]').value;
    const categoryId = document.querySelector('#products-section .form-select').value;
    
    // Panggil loadProducts dengan filter baru
    loadProducts(query, categoryId); 
}

// ** FUNGSI EDIT PRODUCT (Preview Gambar) **
async function editProduct(id) {
    currentProductId = id;
    document.getElementById('productModalTitle').textContent = 'Edit Produk';
    document.getElementById('productImageFile').value = ''; 
    document.getElementById('image-preview').innerHTML = '';
    await loadCategoriesForProductModal();

    try {
        const response = await fetch(`/buyee-admin/products/${id}`);
        const data = await response.json();
        
        document.getElementById('productName').value = data.name;
        document.getElementById('productCategory').value = data.category_id;
        document.getElementById('productPrice').value = data.price;
        document.getElementById('productStock').value = data.stock;
        document.getElementById('productStatus').value = data.is_active;

        const previewDiv = document.getElementById('image-preview');
        if (data.image) {
            const imageUrl = `/${data.image}`; // Path dari root public
            previewDiv.innerHTML = `<img src="${imageUrl}" style="max-width: 150px; border-radius: 8px; margin-top: 10px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);" alt="Foto Produk Saat Ini">`;
        }
    } catch (error) {
        console.error('Gagal mengambil data produk:', error);
    }
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    productModal.show();
}

async function openProductModal() {
    currentProductId = null;
    document.getElementById('productModalTitle').textContent = 'Tambah Produk';
    document.getElementById('productForm').reset();
    document.getElementById('image-preview').innerHTML = ''; // Hapus preview
    await loadCategoriesForProductModal();
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    productModal.show();
}

async function loadCategoriesForProductModal() {
    try {
        const response = await fetch('/buyee-admin/categories');
        const categories = await response.json();
        const select = document.getElementById('productCategory');
        select.innerHTML = '<option value="">Pilih Kategori</option>';
        categories.forEach(c => select.innerHTML += `<option value="${c.id}">${c.name}</option>`);
    } catch (error) {
        console.error('Gagal memuat kategori:', error);
    }
}

async function saveProduct() {
    const form = document.getElementById('productForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    const formData = new FormData(form);
    
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('id', currentProductId || '');

    if (currentProductId && !document.getElementById('productImageFile').files.length) {
        formData.delete('image_file');
    }

    try {
        const response = await fetch('/buyee-admin/products/save', {
            method: 'POST',
            body: formData 
        });
        
        const result = await response.json();
        alert(result.message);
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadProducts();
        }
    } catch (error) {
        console.error('Gagal menyimpan produk:', error);
    }
}

async function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        try {
            const response = await fetch(`/buyee-admin/products/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });
            const result = await response.json();
            alert(result.message);
            if (response.ok) {
                loadProducts();
            }
        } catch (error) {
            console.error('Gagal menghapus produk:', error);
        }
    }
}

async function loadCategories() {
    try {
        const response = await fetch('/buyee-admin/categories');
        const categories = await response.json();
        const tbody = document.querySelector('#categories-table-body');
        tbody.innerHTML = '';
        categories.forEach(c => {
            tbody.innerHTML += `
                <tr>
                    <td>${String(c.id).padStart(3, '0')}</td>
                    <td>${c.name}</td>
                    <td>${c.products_count} produk</td>
                    <td>${formatDate(c.created_at)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="editCategory(${c.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${c.id})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
        });
    } catch (error) {
        console.error('Gagal memuat kategori:', error);
    }
}

function openCategoryModal() {
    document.getElementById('category-id').value = '';
    document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori';
    document.getElementById('categoryForm').reset();
    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    categoryModal.show();
}

async function editCategory(id) {
    document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
    try {
        const response = await fetch(`/buyee-admin/categories/${id}`);
        const data = await response.json();
        document.getElementById('categoryName').value = data.name;
        document.getElementById('category-id').value = data.id;
    } catch (error) {
        console.error('Gagal mengambil data kategori:', error);
    }
    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    categoryModal.show();
}

async function saveCategory() {
    const form = document.getElementById('categoryForm');
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    const categoryData = {
        id: document.getElementById('category-id').value,
        name: document.getElementById('categoryName').value,
    };
    try {
        const response = await fetch('/buyee-admin/categories/save', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify(categoryData)
        });
        const result = await response.json();
        alert(result.message);
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadCategories();
        }
    } catch (error) {
        console.error('Gagal menyimpan kategori:', error);
    }
}

async function deleteCategory(id) {
    if (confirm('Yakin ingin menghapus kategori ini?')) {
        try {
            const response = await fetch(`/buyee-admin/categories/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });
            const result = await response.json();
            alert(result.message);
            if (response.ok) {
                loadCategories();
            }
        } catch (error) {
            console.error('Gagal menghapus kategori:', error);
        }
    }
}

async function loadOrders() {
    try {
        const response = await fetch('/buyee-admin/orders');
        const orders = await response.json();
        const tbody = document.querySelector('#orders-table-body');
        const dashboardTbody = document.querySelector('#dashboard-orders-table-body');
        tbody.innerHTML = '';
        dashboardTbody.innerHTML = '';

        orders.forEach(o => {
            const row = `
                <tr>
                    <td>#${String(o.id).padStart(3, '0')}</td>
                    <td>${o.user ? o.user.name : 'N/A'}</td>
                    <td>${formatCurrency(o.total)}</td>
                    <td>${getStatusBadge(o.status)}</td>
                    <td>${o.address_text}</td>
                    <td>${formatDate(o.created_at)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success btn-custom" onclick="updateOrderStatus(${o.id}, '${o.status}')"><i class="fas fa-edit"></i></button>
                    </td>
                </tr>`;
            tbody.innerHTML += row;
        });

        orders.slice(0, 3).forEach(o => {
            dashboardTbody.innerHTML += `
                <tr>
                    <td>#${String(o.id).padStart(3, '0')}</td>
                    <td>${o.user ? o.user.name : 'N/A'}</td>
                    <td>${formatCurrency(o.total)}</td>
                    <td>${getStatusBadge(o.status)}</td>
                    <td>${formatDate(o.created_at)}</td>
                </tr>`;
        });

    } catch (error) {
        console.error('Gagal memuat pesanan:', error);
    }
}

function updateOrderStatus(id, currentStatus) {
    currentOrderId = id;
    document.getElementById('orderStatus').value = currentStatus;
    const orderStatusModal = new bootstrap.Modal(document.getElementById('orderStatusModal'));
    orderStatusModal.show();
}

async function saveOrderStatus() {
    const status = document.getElementById('orderStatus').value;
    const orderData = { id: currentOrderId, status: status };
    try {
        const response = await fetch('/buyee-admin/orders/update-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: JSON.stringify(orderData)
        });
        const result = await response.json();
        alert(result.message);
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadOrders();
        }
    } catch (error) {
        console.error('Gagal update status pesanan:', error);
    }
}

function logout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        window.location.href = "{{ route('logout') }}";
    }
}

// ** Event Listeners untuk Search dan Filter **
document.addEventListener('DOMContentLoaded', function() {
    showSection('dashboard');
    
    // Attach event listeners for search and filter only once
    const searchInput = document.querySelector('#products-section input[type="search"]');
    const categorySelect = document.querySelector('#productFilterCategory');
    
    if (searchInput) {
        searchInput.addEventListener('input', filterProducts);
    }
    if (categorySelect) {
        categorySelect.addEventListener('change', filterProducts);
    }
});

</script>

</body>
</html>