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
                        <a class="nav-link text-white" href="#" onclick="event.preventDefault(); logout();" style="opacity: 0.8;">
                            <i class="fas fa-sign-out-alt me-2"></i> Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
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
                                    <div class="stat-icon" style="background: linear-gradient(45deg, #FFB6C1, #FFC0CB);"><i class="fas fa-box"></i></div>
                                    <div class="ms-3">
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="products">...</h3><small class="text-muted">Total Produk</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card border-0">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: linear-gradient(45deg, #FF69B4, #FFB6C1);"><i class="fas fa-shopping-cart"></i></div>
                                    <div class="ms-3">
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="orders">...</h3><small class="text-muted">Pesanan Baru</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card border-0">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: linear-gradient(45deg, #FFCCCB, #FFB6C1);"><i class="fas fa-users"></i></div>
                                    <div class="ms-3">
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="users">...</h3><small class="text-muted">Total Pengguna</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card stat-card border-0">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center">
                                    <div class="stat-icon" style="background: linear-gradient(45deg, #FFC0CB, #FF69B4);"><i class="fas fa-money-bill-wave"></i></div>
                                    <div class="ms-3">
                                        <h3 class="mb-0" style="color: #8B5A6B;" data-stat="sales">...</h3><small class="text-muted">Total Penjualan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-container">
                    <div class="p-3 border-bottom"><h5 class="mb-0" style="color: #8B5A6B;">Pesanan Terbaru</h5></div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID Pesanan</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody id="dashboard-orders-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="products-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="search" class="form-control me-2" placeholder="Cari produk..." style="width: 300px;">
                        <select class="form-select" style="width: 150px;" id="productFilterCategory"><option value="">Semua Kategori</option></select>
                    </div>
                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#productModal" onclick="openProductModal()"><i class="fas fa-plus me-2"></i>Tambah Produk</button>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th><th>Foto</th><th>Nama Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="products-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="orders-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center">
                        <input type="search" class="form-control me-2" placeholder="Cari pesanan..." style="width: 300px;">
                        <select class="form-select" style="width: 150px;">
                            <option>Semua Status</option><option>Diproses</option><option>Dikirim</option><option>Selesai</option><option>Batal</option>
                        </select>
                    </div>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th><th>Pelanggan</th><th>Total</th><th>Status</th><th>Pembayaran</th><th>Alamat</th><th>Tanggal</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="orders-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div id="categories-section" style="display: none;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div><input type="search" class="form-control" placeholder="Cari kategori..." style="width: 300px;"></div>
                    <button class="btn btn-primary btn-custom" data-bs-toggle="modal" data-bs-target="#categoryModal" onclick="openCategoryModal()"><i class="fas fa-plus me-2"></i>Tambah Kategori</button>
                </div>
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th>ID</th><th>Nama Kategori</th><th>Jumlah Produk</th><th>Tanggal Dibuat</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="categories-table-body"></tbody>
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
                        <div class="col-md-6 mb-3"><label class="form-label">Nama Produk</label><input type="text" class="form-control" id="productName" name="name" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Kategori</label><select class="form-select" id="productCategory" name="category_id" required><option value="">Pilih Kategori</option></select></div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3"><label class="form-label">Harga</label><input type="number" class="form-control" id="productPrice" name="price" required></div>
                        <div class="col-md-6 mb-3"><label class="form-label">Stok</label><input type="number" class="form-control" id="productStock" name="stock" required></div>
                    </div>
                    <div class="mb-3"><label class="form-label">Foto Produk</label><input type="file" class="form-control" id="productImageFile" name="image_file" accept=".jpeg,.jpg,.png,.gif,.webp"><small class="text-muted mt-2">Format: JPEG, PNG, JPG, GIF, WebP. Maksimal 2MB. Kosongkan jika tidak ingin mengubah foto.</small><div id="image-preview" class="mt-2"></div></div>
                    <div class="mb-3"><label class="form-label">Deskripsi Produk</label><textarea class="form-control" id="productDescription" name="description" rows="4" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Status</label><select class="form-select" id="productStatus" name="is_active"><option value="1">Aktif</option><option value="0">Tidak Aktif</option></select></div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-primary" onclick="saveProduct()">Simpan</button></div>
        </div>
    </div>
</div>
<div class="modal fade" id="orderStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header"><h5 class="modal-title">Update Status Pesanan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="orderStatusForm"><input type="hidden" id="order-id" name="id"><div class="mb-3"><label class="form-label">Status Pesanan</label><select class="form-select" id="orderStatus" name="status" required><option value="diproses">Diproses</option><option value="dikirim">Dikirim</option><option value="selesai">Selesai</option><option value="batal">Batal</option></select></div></form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-primary" onclick="saveOrderStatus()">Update Status</button></div>
        </div>
    </div>
</div>
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 20px; border: none;">
            <div class="modal-header"><h5 class="modal-title" id="categoryModalTitle">Tambah Kategori</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <form id="categoryForm"><input type="hidden" id="category-id" name="id"><div class="mb-3"><label class="form-label">Nama Kategori</label><input type="text" class="form-control" id="categoryName" name="name" required></div></form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button><button type="button" class="btn btn-primary" onclick="saveCategory()" id="saveCategoryBtn">Simpan</button></div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
// [PERBAIKAN] Kode JavaScript dengan URL API yang benar
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
        'batal': 'bg-danger',
        'pending': 'bg-warning' // Tambahkan pending
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
    if (section === 'dashboard') {
        loadDashboardStats();
        loadRecentOrders();
    }
    if (section === 'products') {
        loadProducts(); // Panggil tanpa filter awal
        loadCategoriesForProductSection();
    }
    if (section === 'orders') {
        loadOrders(); // Panggil tanpa filter awal
    }
    if (section === 'categories') loadCategories();
}

async function loadDashboardStats() {
    try {
        console.log('Loading dashboard stats...');
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/stats', {
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        console.log('Stats response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        console.log('Stats data received:', data);
        
        // Update statistics
        const productsElement = document.querySelector('#dashboard-section h3[data-stat="products"]');
        const ordersElement = document.querySelector('#dashboard-section h3[data-stat="orders"]');
        const usersElement = document.querySelector('#dashboard-section h3[data-stat="users"]');
        const salesElement = document.querySelector('#dashboard-section h3[data-stat="sales"]');
        
        if (productsElement) productsElement.textContent = data.total_products || 0;
        if (ordersElement) ordersElement.textContent = data.new_orders || 0;
        if (usersElement) usersElement.textContent = data.total_users || 0;
        if (salesElement) salesElement.textContent = formatCurrency(data.total_sales || 0);
        
        console.log('Dashboard stats updated successfully');
    } catch (error) {
        console.error('Gagal memuat statistik:', error);
        // alert('Gagal memuat statistik dashboard: ' + error.message);
    }
}

async function loadRecentOrders() {
    try {
        console.log('Loading recent orders for dashboard...');
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/orders', {
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const orders = await response.json();
        console.log('Recent orders data received:', orders);
        
        const dashboardTbody = document.querySelector('#dashboard-orders-table-body');
        if (!dashboardTbody) {
            console.error('Dashboard orders table body not found');
            return;
        }
        
        dashboardTbody.innerHTML = '';
        
        // Show only the 5 most recent orders
        const recentOrders = orders.slice(0, 5);
        if (recentOrders.length === 0) {
            dashboardTbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada pesanan terbaru.</td></tr>';
            return;
        }

        recentOrders.forEach(order => {
            // Menggunakan properti customer_name dari order object
            const customerName = order.customer_name || (order.user ? order.user.name : 'N/A'); 
            const row = `
                <tr>
                    <td>#${String(order.id).padStart(3, '0')}</td>
                    <td>${customerName}</td>
                    <td>${formatCurrency(order.total || 0)}</td>
                    <td>${getStatusBadge(order.status || 'pending')}</td>
                    <td>${formatDate(order.created_at)}</td>
                </tr>`;
            dashboardTbody.innerHTML += row;
        });
        
        console.log('Recent orders loaded successfully');
    } catch (error) {
        console.error('Gagal memuat pesanan terbaru:', error);
        const dashboardTbody = document.querySelector('#dashboard-orders-table-body');
        if (dashboardTbody) {
            dashboardTbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Gagal memuat data pesanan</td></tr>';
        }
    }
}

async function loadCategoriesForProductSection() {
    try {
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/categories');
        const categories = await response.json();
        const select = document.querySelector('#productFilterCategory');
        if (!select) return;
        select.innerHTML = '<option value="">Semua Kategori</option>';
        categories.forEach(c => select.innerHTML += `<option value="${c.id}">${c.name}</option>`);
        
        // Juga update dropdown di Product Modal
        const modalSelect = document.getElementById('productCategory');
        if (modalSelect) {
            modalSelect.innerHTML = '<option value="">Pilih Kategori</option>';
            categories.forEach(c => modalSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
        }
        
    } catch (error) {
        console.error('Gagal memuat kategori untuk filter:', error);
    }
}

// >> PERBAIKAN FUNGSI LOAD PRODUCTS (Menambahkan Parameter Filter)
async function loadProducts(query = '', categoryId = '') {
    try {
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        let url = '/admin/api/products';
        const params = [];
        if (query) params.push(`query=${encodeURIComponent(query)}`);
        if (categoryId) params.push(`category_id=${categoryId}`);
        if (params.length > 0) url += '?' + params.join('&');
        
        console.log('Fetching products from:', url);
        const response = await fetch(url);
        const products = await response.json();
        const tbody = document.querySelector('#products-table-body');
        tbody.innerHTML = '';
        if (products.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td></tr>`;
            return;
        }
        products.forEach(p => {
            // Perhatikan bahwa field stock di AdminController.php lama adalah stock_quantity
            const stockValue = p.stock_quantity !== undefined ? p.stock_quantity : (p.stock || 0);
            const statusText = stockValue > 0 && p.is_active ? 'Aktif' : 'Habis/Nonaktif';
            const statusBadgeClass = stockValue > 0 && p.is_active ? 'bg-success' : 'bg-danger';
            
            // Perbaikan untuk image
            let imageUrl = 'https://via.placeholder.com/50x50?text=No+Img';
            if (p.images && p.images.length > 0) {
                // Di AdminController.php yang lama, field-nya adalah 'images' (JSON array)
                // Kita asumsikan itu adalah array of paths, dan kita ambil yang pertama.
                imageUrl = `/storage/${p.images[0]}`; 
            } else if (p.image) {
                 // Untuk kode yang menggunakan kolom 'image' (seperti di AdminController.php yang lama yang direvisi)
                 imageUrl = `/${p.image}`; 
            }
            
            const imageCell = `<td><img src="${imageUrl}" alt="${p.name}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;" onerror="this.onerror=null;this.src='https://via.placeholder.com/50x50?text=No+Img';"></td>`;
            tbody.innerHTML += `
                <tr>
                    <td>${String(p.id).padStart(3, '0')}</td>
                    ${imageCell}
                    <td>${p.name}</td>
                    <td>${p.category ? p.category.name : 'N/A'}</td>
                    <td>${formatCurrency(p.price)}</td>
                    <td>${stockValue}</td>
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

// >> FUNGSI BARU UNTUK FILTER PRODUK
function filterProducts() {
    const query = document.querySelector('#products-section input[type="search"]').value;
    const categoryId = document.querySelector('#productFilterCategory').value;
    loadProducts(query, categoryId);
}

async function editProduct(id) {
    currentProductId = id;
    document.getElementById('productModalTitle').textContent = 'Edit Produk';
    document.getElementById('productForm').reset();
    document.getElementById('image-preview').innerHTML = '';
    await loadCategoriesForProductSection(); // Panggil fungsi yang sudah diupdate
    try {
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch(`/admin/api/products/${id}`);
        const data = await response.json();
        document.getElementById('productName').value = data.name;
        document.getElementById('productCategory').value = data.category_id;
        document.getElementById('productPrice').value = data.price;
        // Gunakan properti yang benar dari Model (stock_quantity) atau fallback ke 'stock'
        document.getElementById('productStock').value = data.stock_quantity !== undefined ? data.stock_quantity : (data.stock || 0); 
        document.getElementById('productStatus').value = data.is_active;
        document.getElementById('productDescription').value = data.description || '';
        const previewDiv = document.getElementById('image-preview');
        
        // Logika Image untuk Edit
        let imageUrl = null;
        if (data.images && data.images.length > 0) {
            imageUrl = `/storage/${data.images[0]}`; 
        } else if (data.image) { 
             imageUrl = `/${data.image}`;
        }
        
        if (imageUrl) {
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
    document.getElementById('image-preview').innerHTML = '';
    await loadCategoriesForProductSection(); // Panggil fungsi yang sudah diupdate
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    productModal.show();
}

// Fungsi loadCategoriesForProductModal DIHAPUS, digantikan oleh loadCategoriesForProductSection

async function saveProduct() {
    console.log('Saving product...');
    const form = document.getElementById('productForm');
    if (!form.checkValidity()) {
        console.log('Form validation failed');
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    formData.append('id', currentProductId || '');
    
    // Log form data for debugging
    console.log('Form data:');
    for (let [key, value] of formData.entries()) {
        console.log(key, value);
    }
    
    // Hapus image_file dari formData jika edit dan tidak ada file baru diupload
    if (currentProductId && !document.getElementById('productImageFile').files.length) {
        formData.delete('image_file');
    }
    
    try {
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/products/save', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        console.log('Save response status:', response.status);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Save response error:', errorText);
            
            let errorMessage = `HTTP ${response.status}`;
            try {
                const errorJson = JSON.parse(errorText);
                if (errorJson.message) {
                    errorMessage += `: ${errorJson.message}`;
                } else if (errorJson.errors) {
                    errorMessage += ': Validasi gagal, cek konsol.';
                    // Tampilkan error validasi Laravel jika ada
                    const errors = errorJson.errors;
                    let validationMessage = '';
                    for (const field in errors) {
                        validationMessage += errors[field].join('\n') + '\n';
                    }
                    alert('Gagal menyimpan produk. Validasi:\n' + validationMessage.trim());
                    return;
                }
            } catch (e) {
                // Jika bukan JSON
            }
            throw new Error(errorMessage);
        }
        
        const result = await response.json();
        console.log('Save result:', result);
        alert(result.message);
        
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadProducts();
        }
    } catch (error) {
        console.error('Gagal menyimpan produk:', error);
        alert('Gagal menyimpan produk: ' + error.message);
    }
}

async function deleteProduct(id) {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        try {
            // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
            const response = await fetch(`/admin/api/products/delete/${id}`, {
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
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/categories');
        const categories = await response.json();
        const tbody = document.querySelector('#categories-table-body');
        tbody.innerHTML = '';
        if (categories.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada kategori.</td></tr>`;
            return;
        }
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
    currentCategoryId = null;
    document.getElementById('category-id').value = '';
    document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori';
    document.getElementById('categoryForm').reset();
    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    categoryModal.show();
}

async function editCategory(id) {
    currentCategoryId = id;
    document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
    try {
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch(`/admin/api/categories/${id}`);
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
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/categories/save', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify(categoryData)
        });
        
        // Cek jika response bukan OK (misal: 400, 422, 500)
        if (!response.ok) {
            const errorText = await response.text();
            console.error('Save category response error:', errorText);
            
            let errorMessage = `HTTP ${response.status}`;
            try {
                const errorJson = JSON.parse(errorText);
                if (errorJson.message) {
                    errorMessage += `: ${errorJson.message}`;
                } else if (errorJson.errors) {
                    errorMessage += ': Validasi gagal, cek konsol.';
                    // Tampilkan error validasi Laravel jika ada
                    const errors = errorJson.errors;
                    let validationMessage = '';
                    for (const field in errors) {
                        validationMessage += errors[field].join('\n') + '\n';
                    }
                    alert('Gagal menyimpan kategori. Validasi:\n' + validationMessage.trim());
                    return;
                }
            } catch (e) {
                // Jika bukan JSON, tampilkan saja status HTTP
            }
            throw new Error(errorMessage); 
        }
        
        // Respons yang sukses
        const result = await response.json();
        alert(result.message);
        
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadCategories();
            loadCategoriesForProductSection(); // Refresh category list in product section
        }
    } catch (error) {
        console.error('Gagal menyimpan kategori:', error);
        alert('Gagal menyimpan kategori: ' + error.message);
    }
}

async function deleteCategory(id) {
    if (confirm('Yakin ingin menghapus kategori ini? Semua produk di dalamnya mungkin tidak akan tampil. Lanjutkan?')) {
        try {
            // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
            const response = await fetch(`/admin/api/categories/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });
            
            if (!response.ok) {
                const errorJson = await response.json();
                if (response.status === 409) { // Konflik, kategori masih punya produk
                    alert(errorJson.message);
                    return;
                }
                throw new Error(errorJson.message || `HTTP ${response.status}`);
            }
            
            const result = await response.json();
            alert(result.message);
            if (response.ok) {
                loadCategories();
                loadCategoriesForProductSection(); // Refresh category list in product section
            }
        } catch (error) {
            console.error('Gagal menghapus kategori:', error);
            alert('Gagal menghapus kategori: ' + error.message);
        }
    }
}

// >> PERBAIKAN FUNGSI LOAD ORDERS (Menambahkan Parameter Filter)
async function loadOrders(query = '', status = 'Semua Status') {
    try {
        let url = '/admin/api/orders';
        const params = [];
        if (query) params.push(`search=${encodeURIComponent(query)}`); // Menggunakan 'search' sesuai AdminController
        if (status !== 'Semua Status') params.push(`status=${encodeURIComponent(status)}`);
        if (params.length > 0) url += '?' + params.join('&');
        
        console.log('Fetching orders from:', url);
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch(url);
        const orders = await response.json();
        const tbody = document.querySelector('#orders-table-body');
        tbody.innerHTML = '';
        
        if (orders.length === 0) {
            tbody.innerHTML = `<tr><td colspan="8" class="text-center text-muted py-4">Tidak ada pesanan ditemukan.</td></tr>`;
            return;
        }
        
        orders.forEach(o => {
            const customerName = o.customer_name || (o.user ? o.user.name : 'N/A');
            const customerEmail = o.customer_email || (o.user ? o.user.email : 'N/A');

            const row = `
                <tr>
                    <td>#${String(o.id).padStart(3, '0')}</td>
                    <td>
                        <strong>${customerName}</strong><br>
                        <small class="text-muted">${customerEmail}</small><br>
                        <small class="text-muted">${o.customer_phone || ''}</small>
                    </td>
                    <td>${formatCurrency(o.total)}</td>
                    <td>${getStatusBadge(o.status)}</td>
                    <td>
                        <span class="badge bg-info">${o.payment_method || 'N/A'}</span>
                    </td>
                    <td>
                        <small>${o.address_text || 'N/A'}</small>
                    </td>
                    <td>${formatDate(o.created_at)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-success btn-custom" onclick="updateOrderStatus(${o.id}, '${o.status}')"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-info btn-custom" data-bs-toggle="modal" data-bs-target="#orderDetailsModal" onclick="viewOrderDetails(${o.id})"><i class="fas fa-eye"></i></button>
                    </td>
                </tr>`;
            tbody.innerHTML += row;
        });
        
        // Panggil loadRecentOrders di sini untuk memastikan data dashboard terbarui
        if (document.getElementById('dashboard-section').style.display === 'block') {
             loadRecentOrders();
        }
        
    } catch (error) {
        console.error('Gagal memuat pesanan:', error);
        document.querySelector('#orders-table-body').innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Error memuat data pesanan: ${error.message}</td></tr>`;
    }
}

// >> FUNGSI BARU UNTUK FILTER PESANAN
function filterOrders() {
    const query = document.querySelector('#orders-section input[type="search"]').value;
    const status = document.querySelector('#orders-section select.form-select').value;
    loadOrders(query, status);
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
        // >> KOREKSI URL API: Menggunakan URL yang benar dari AdminController.php
        const response = await fetch('/admin/api/orders/update-status', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify(orderData)
        });
        
        if (!response.ok) {
            const errorJson = await response.json();
            throw new Error(errorJson.message || `HTTP ${response.status}`);
        }
        
        const result = await response.json();
        alert(result.message);
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadOrders(); // Refresh order list
        }
    } catch (error) {
        console.error('Gagal update status pesanan:', error);
        alert('Gagal update status pesanan: ' + error.message);
    }
}

function logout() {
    if (confirm('Apakah Anda yakin ingin keluar?')) {
        document.getElementById('logout-form').submit();
    }
}

async function viewOrderDetails(orderId) {
    try {
        // Karena API getOrders sudah mengembalikan detail item, kita panggil ulang
        // Ini tidak ideal, tapi ini cara termudah untuk berintegrasi tanpa membuat API baru.
        const response = await fetch('/admin/api/orders'); 
        const orders = await response.json();
        const order = orders.find(o => o.id === orderId);
        
        if (!order) {
            alert('Pesanan tidak ditemukan');
            return;
        }
        
        const content = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Informasi Pesanan</h6>
                    <p><strong>ID Pesanan:</strong> #${String(order.id).padStart(3, '0')}</p>
                    <p><strong>Status:</strong> ${getStatusBadge(order.status)}</p>
                    <p><strong>Total:</strong> ${formatCurrency(order.total)}</p>
                    <p><strong>Metode Pembayaran:</strong> <span class="badge bg-info">${order.payment_method || 'N/A'}</span></p>
                    <p><strong>Tanggal Pesanan:</strong> ${formatDate(order.created_at)}</p>
                    ${order.order_notes ? `<p><strong>Catatan:</strong> ${order.order_notes}</p>` : ''}
                </div>
                <div class="col-md-6">
                    <h6>Informasi Pelanggan</h6>
                    <p><strong>Nama:</strong> ${order.customer_name || (order.user ? order.user.name : 'N/A')}</p>
                    <p><strong>Email:</strong> ${order.customer_email || (order.user ? order.user.email : 'N/A')}</p>
                    <p><strong>Telepon:</strong> ${order.customer_phone || 'N/A'}</p>
                    <p><strong>Alamat:</strong> ${order.address_text || 'N/A'}</p>
                </div>
            </div>
            <hr>
            <h6>Item Pesanan</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Qty</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${(order.items || []).map(item => `
                            <tr>
                                <td>${item.product_name}</td>
                                <td>${item.qty}</td>
                                <td>${formatCurrency(item.price)}</td>
                                <td>${formatCurrency(item.subtotal)}</td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            </div>
        `;
        
        document.getElementById('orderDetailsContent').innerHTML = content;
        const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
        modal.show();
    } catch (error) {
        console.error('Gagal memuat detail pesanan:', error);
        alert('Gagal memuat detail pesanan');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Check if there's an activeSection parameter from session
    @if(session('activeSection'))
        showSection('{{ session('activeSection') }}');
    @else
        showSection('dashboard');
    @endif
    
    // Event Listeners untuk Filter Produk
    const productSearchInput = document.querySelector('#products-section input[type="search"]');
    const productCategorySelect = document.querySelector('#productFilterCategory');
    
    if (productSearchInput) {
        productSearchInput.addEventListener('input', filterProducts);
    }
    if (productCategorySelect) {
        productCategorySelect.addEventListener('change', filterProducts);
    }
    
    // Event Listeners untuk Filter Pesanan
    const orderSearchInput = document.querySelector('#orders-section input[type="search"]');
    const orderStatusSelect = document.querySelector('#orders-section .form-select');

    if (orderSearchInput) {
        orderSearchInput.addEventListener('input', filterOrders);
    }
    if (orderStatusSelect) {
        orderStatusSelect.addEventListener('change', filterOrders);
    }
});
</script>

<div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderDetailsModalLabel">Detail Pesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>