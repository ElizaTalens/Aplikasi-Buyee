<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin - Buyee</title>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<style>
:root{
  --nav-dark: #4c454d;
  --accent-pink: #f27ca5;
  --soft-bg: #f7f7f7;
  --card-bg: #ffffff;
  --muted-text: #6b7280;
  --text-dark: #2a242b;
  --success: #22c55e;
  --warning: #f59e0b;
  --danger: #ef4444;
}

html, body, input, button, select, textarea, a, p, h1, h2, h3, h4, h5, h6, .nav-link, .btn, .table {
  font-family: "Inter", system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
}

body {
  background-color: var(--soft-bg);
  color: var(--text-dark);
}

.sidebar {
  min-height: 100vh;
  background: linear-gradient(180deg, var(--nav-dark) 0%, #352a30 100%);
  color: #fff;
  box-shadow: 2px 0 18px rgba(42,36,43,0.06);
}

.sidebar .nav-link {
  color: rgba(255,255,255,0.92);
  padding: 10px 16px;
  border-radius: 10px;
  margin: 6px 0;
  transition: transform .18s ease, background .18s ease, color .18s ease;
}
.sidebar .nav-link i {
  color: rgba(255,255,255,0.9);
  width: 18px;
  text-align: center;
}
.sidebar .nav-link:hover,
.sidebar .nav-link.active {
  background: rgba(255,255,255,0.05);
  transform: translateX(6px);
  color: #fff;
  text-decoration: none;
}


.stat-card,
.table-container {
  background: var(--card-bg);
  border-radius: 14px;
  border: 1px solid rgba(42,36,43,0.04);
  box-shadow: 0 8px 22px rgba(42,36,43,0.04);
  transition: transform .18s ease, box-shadow .18s ease;
}

/* Stat card hover  */
.stat-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 18px 40px rgba(42,36,43,0.08);
}

/* Stat icon accent */
.stat-icon {
  width: 56px;
  height: 56px;
  display:inline-grid;
  place-items:center;
  border-radius:10px;
  background: linear-gradient(135deg, #f27ca5 0%, #ffb6c1 100%);
  color: #fff;
  font-size:18px;
  box-shadow: 0 6px 18px rgba(42,36,43,0.06);
  transition: transform .16s ease, box-shadow .16s ease;
}

/* Stat icon hover kecil agar ada feedback */
.stat-icon:hover {
  transform: translateY(-3px) scale(1.03);
  box-shadow: 0 12px 30px rgba(42,36,43,0.10);
}

/* Primary button uses accent pink */
.btn-primary {
  background: linear-gradient(90deg, var(--accent-pink), #ff95b8);
  border: none;
  color: #fff;
  transition: filter .12s ease, box-shadow .12s ease, transform .12s ease;
}
.btn-primary:hover {
  filter: brightness(.98);
  box-shadow: 0 10px 26px rgba(242,124,165,0.16);
  transform: translateY(-2px);
}

/* Keep table row hover */
.table-hover tbody tr:hover { background-color: rgba(42,36,43,0.03); }

/* Badges */
.bg-success { background: linear-gradient(90deg,#dcfce7,#bbf7d0) !important; color: var(--success) !important; }
.bg-warning { background: linear-gradient(90deg,#fff7ed,#ffedd5) !important; color: var(--warning) !important; }
.bg-info    { background: linear-gradient(90deg,#eef2ff,#e9f0ff) !important; color:#0b69a3 !important; }
.bg-danger  { background: linear-gradient(90deg,#ffe6ea,#ffd1df) !important; color: var(--danger) !important; }

/* Inputs focus warna aksen */
.form-control:focus, .form-select:focus {
  border-color: var(--accent-pink);
  box-shadow: 0 0 0 0.12rem rgba(242,124,165,0.12);
}

/* Page title color */
.page-title { color: var(--nav-dark); font-weight: 700; }

/* small muted helper */
.text-muted { color: var(--muted-text) !important; }

/* responsive tweak tetap sama fungsionalitas */
@media (max-width: 900px) {
  .sidebar { position: static; width: 100%; display:flex; gap:8px; overflow:auto; }
}
</style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
            <div class="position-sticky pt-4">
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
        'pending': 'bg-warning' 
    };
    return `<span class="badge status-badge ${statuses[status] || 'bg-secondary'}">${status.charAt(0).toUpperCase() + status.slice(1)}</span>`;
}


function closeAllModalsAndCleanUp() {
    document.querySelectorAll('.modal.show').forEach(modal => {
        try {
            const inst = bootstrap.Modal.getInstance(modal) || new bootstrap.Modal(modal);
            inst.hide();
        } catch (e) {
            modal.classList.remove('show');
            modal.style.display = 'none';
        }
    });

    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
}

document.addEventListener('hidden.bs.modal', function () {
    setTimeout(() => {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }, 50);
});


function showSection(section) {
    // HAPUS modal / backdrop sebelum mengganti section
    closeAllModalsAndCleanUp();

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
        loadProducts(); 
        loadCategoriesForProductSection();
    }
    if (section === 'orders') {
        loadOrders(); 
    }
    if (section === 'categories') loadCategories();
}

async function loadDashboardStats() {
    try {
        console.log('Loading dashboard stats...');
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
    }
}

async function loadRecentOrders() {
    try {
        console.log('Loading recent orders for dashboard...');
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
        const recentOrders = orders.slice(0, 5);
        if (recentOrders.length === 0) {
            dashboardTbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Tidak ada pesanan terbaru.</td></tr>';
            return;
        }

        recentOrders.forEach(order => {
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
        const response = await fetch('/admin/api/categories');
        const categories = await response.json();
        const select = document.querySelector('#productFilterCategory');
        if (!select) return;
        select.innerHTML = '<option value="">Semua Kategori</option>';
        categories.forEach(c => select.innerHTML += `<option value="${c.id}">${c.name}</option>`);
    
        const modalSelect = document.getElementById('productCategory');
        if (modalSelect) {
            modalSelect.innerHTML = '<option value="">Pilih Kategori</option>';
            categories.forEach(c => modalSelect.innerHTML += `<option value="${c.id}">${c.name}</option>`);
        }
        
    } catch (error) {
        console.error('Gagal memuat kategori untuk filter:', error);
    }
}

async function loadProducts(query = '', categoryId = '') {
    try {
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
                    const errors = errorJson.errors;
                    let validationMessage = '';
                    for (const field in errors) {
                        validationMessage += errors[field].join('\n') + '\n';
                    }
                    alert('Gagal menyimpan produk. Validasi:\n' + validationMessage.trim());
                    return;
                }
            } catch (e) {
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
        const response = await fetch('/admin/api/categories/save', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
            },
            body: JSON.stringify(categoryData)
        });
        
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
                    const errors = errorJson.errors;
                    let validationMessage = '';
                    for (const field in errors) {
                        validationMessage += errors[field].join('\n') + '\n';
                    }
                    alert('Gagal menyimpan kategori. Validasi:\n' + validationMessage.trim());
                    return;
                }
            } catch (e) {
            }
            throw new Error(errorMessage); 
        }
        
        const result = await response.json();
        alert(result.message);
        
        if (response.ok) {
            closeAllModalsAndCleanUp();
            loadCategories();
            loadCategoriesForProductSection();
        }
    } catch (error) {
        console.error('Gagal menyimpan kategori:', error);
        alert('Gagal menyimpan kategori: ' + error.message);
    }
}

async function deleteCategory(id) {
    if (confirm('Yakin ingin menghapus kategori ini? Semua produk di dalamnya mungkin tidak akan tampil. Lanjutkan?')) {
        try {
            const response = await fetch(`/admin/api/categories/delete/${id}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') }
            });
            
            if (!response.ok) {
                const errorJson = await response.json();
                if (response.status === 409) {
                    alert(errorJson.message);
                    return;
                }
                throw new Error(errorJson.message || `HTTP ${response.status}`);
            }
            
            const result = await response.json();
            alert(result.message);
            if (response.ok) {
                loadCategories();
                loadCategoriesForProductSection(); 
            }
        } catch (error) {
            console.error('Gagal menghapus kategori:', error);
            alert('Gagal menghapus kategori: ' + error.message);
        }
    }
}

async function loadOrders(query = '', status = 'Semua Status') {
    try {
        let url = '/admin/api/orders';
        const params = [];
        if (query) params.push(`search=${encodeURIComponent(query)}`); 
        if (status !== 'Semua Status') params.push(`status=${encodeURIComponent(status)}`);
        if (params.length > 0) url += '?' + params.join('&');
        
        console.log('Fetching orders from:', url);
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
        
        if (document.getElementById('dashboard-section').style.display === 'block') {
             loadRecentOrders();
        }
        
    } catch (error) {
        console.error('Gagal memuat pesanan:', error);
        document.querySelector('#orders-table-body').innerHTML = `<tr><td colspan="8" class="text-center text-danger py-4">Error memuat data pesanan: ${error.message}</td></tr>`;
    }
}


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
            loadOrders(); 
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
    @if(session('activeSection'))
        showSection('{{ session('activeSection') }}');
    @else
        showSection('dashboard');
    @endif
    
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