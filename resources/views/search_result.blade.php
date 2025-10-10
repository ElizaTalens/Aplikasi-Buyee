<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Result</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #f8a9c2;
            --secondary-color: #f5459dff;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #ffe4edff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .search-header {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .search-bar {
            position: relative;
        }

        .search-bar input {
            border-radius: 25px;
            padding: 0.8rem 3rem 0.8rem 1.5rem;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .search-bar input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            color: white;
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            border: none;
            position: sticky;
            top: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
        }

        .product-image {
            height: 200px;
            background: linear-gradient(45deg, #f8f9fa, #e9ecef);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: #adb5bd;
            position: relative;
        }

        .product-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--success-color);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .product-badge.sale {
            background: var(--danger-color);
        }

        .product-title {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .product-price {
            color: var(--success-color);
            font-weight: 700;
            font-size: 1.1rem;
        }

        .product-price .old-price {
            color: #6c757d;
            text-decoration: line-through;
            font-size: 0.9rem;
            font-weight: 400;
        }

        .btn-add-cart {
            background: linear-gradient(135deg, var(--success-color) 0%, #20c997 100%);
            border: none;
            border-radius: 25px;
            padding: 0.5rem 1rem;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-add-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.3);
            color: white;
        }

        .category-filter .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .result-info {
            background: linear-gradient(135deg, #f8a9c2 0%, #f5459dff 100%);
            color: white;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .pagination .page-link {
            border-radius: 25px !important;
            margin: 0 2px;
            border: none;
            color: var(--primary-color);
        }

        .pagination .page-item.active .page-link {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }

        .no-results {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .no-results i {
            font-size: 4rem;
            color: #adb5bd;
            margin-bottom: 1rem;
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

        .product-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        .product-card:nth-child(1) { animation-delay: 0.1s; }
        .product-card:nth-child(2) { animation-delay: 0.2s; }
        .product-card:nth-child(3) { animation-delay: 0.3s; }
        .product-card:nth-child(4) { animation-delay: 0.4s; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                        <a class="nav-link active" href="/pencarian">
                            <i class="fas fa-search me-1"></i>Pencarian
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart me-1"></i>
                            Keranjang <span class="badge bg-warning">3</span>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user me-1"></i>John Doe
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="profile.php">Profil</a></li>
                            <li><a class="dropdown-item" href="/pesanan-saya">Pesanan Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="search-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="search-bar">
                        <input type="text" class="form-control" id="searchInput" 
                               placeholder="Cari produk atau kategori..." value="kaos">
                        <button class="search-btn" onclick="performSearch()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                    <select class="form-select" id="sortBy" onchange="sortProducts()">
                        <option value="relevance">Paling Relevan</option>
                        <option value="newest">Terbaru</option>
                        <option value="price_low">Harga Terendah</option>
                        <option value="price_high">Harga Tertinggi</option>
                        <option value="popular">Terpopuler</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="card filter-card">
                    <div class="card-body">
                        <h6 class="card-title">
                            <i class="fas fa-filter text-primary me-2"></i>
                            Filter Pencarian
                        </h6>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold">Kategori</h6>
                            <div class="category-filter">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="fashion" id="fashion" checked>
                                    <label class="form-check-label" for="fashion">
                                        Fashion (25)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="elektronik" id="elektronik">
                                    <label class="form-check-label" for="elektronik">
                                        Elektronik (18)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="makanan" id="makanan">
                                    <label class="form-check-label" for="makanan">
                                        Makanan & Minuman (12)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="kesehatan" id="kesehatan">
                                    <label class="form-check-label" for="kesehatan">
                                        Kesehatan & Kecantikan (8)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="olahraga" id="olahraga">
                                    <label class="form-check-label" for="olahraga">
                                        Olahraga (15)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Range Harga</h6>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" placeholder="Min" id="minPrice">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" placeholder="Max" id="maxPrice">
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100 mt-2" onclick="applyPriceFilter()">
                                Terapkan
                            </button>
                        </div>

                        <div class="mb-4">
                            <h6 class="fw-bold">Rating</h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="5" id="rating5">
                                <label class="form-check-label" for="rating5">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    (5 bintang)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="4" id="rating4">
                                <label class="form-check-label" for="rating4">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-muted"></i>
                                    (4+ bintang)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="3" id="rating3">
                                <label class="form-check-label" for="rating3">
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="fas fa-star text-warning"></i>
                                    <i class="far fa-star text-muted"></i>
                                    <i class="far fa-star text-muted"></i>
                                    (3+ bintang)
                                </label>
                            </div>
                        </div>

                        <button class="btn btn-outline-secondary w-100" onclick="clearAllFilters()">
                            <i class="fas fa-times me-2"></i>Hapus Semua Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="result-info">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-search me-2"></i>
                            <strong>Hasil pencarian untuk: "kaos"</strong>
                            <br><small>Ditemukan 15 produk dari 78 total produk</small>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark">Page 1 of 2</span>
                        </div>
                    </div>
                </div>

                <div class="row" id="productsContainer">
                    <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="fashion" data-price="75000">
                        <div class="card product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <div class="product-badge">Stok: 50</div>
                            </div>
                            <div class="card-body">
                                <h6 class="product-title">Kaos Polos Premium Cotton</h6>
                                <p class="text-muted small mb-2">Fashion • Unisex</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="small">4.8 (125 review)</span>
                                </div>
                                <div class="product-price mb-3">
                                    Rp 75.000
                                    <span class="old-price ms-2">Rp 85.000</span>
                                </div>
                                <button class="btn btn-add-cart w-100" onclick="addToCart(1, 'Kaos Polos Premium')">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="fashion" data-price="120000">
                        <div class="card product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <div class="product-badge sale">Sale!</div>
                            </div>
                            <div class="card-body">
                                <h6 class="product-title">Kaos Distro Original Design</h6>
                                <p class="text-muted small mb-2">Fashion • Limited Edition</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="small">4.6 (89 review)</span>
                                </div>
                                <div class="product-price mb-3">
                                    Rp 120.000
                                    <span class="old-price ms-2">Rp 150.000</span>
                                </div>
                                <button class="btn btn-add-cart w-100" onclick="addToCart(2, 'Kaos Distro Original')">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="fashion" data-price="95000">
                        <div class="card product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <div class="product-badge">New</div>
                            </div>
                            <div class="card-body">
                                <h6 class="product-title">Kaos Olahraga Dry-Fit</h6>
                                <p class="text-muted small mb-2">Olahraga • Breathable</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="small">4.9 (67 review)</span>
                                </div>
                                <div class="product-price mb-3">
                                    Rp 95.000
                                </div>
                                <button class="btn btn-add-cart w-100" onclick="addToCart(3, 'Kaos Olahraga Dry-Fit')">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="fashion" data-price="65000">
                        <div class="card product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <div class="product-badge">Stok: 25</div>
                            </div>
                            <div class="card-body">
                                <h6 class="product-title">Kaos Anak Karakter Lucu</h6>
                                <p class="text-muted small mb-2">Fashion • Kids</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="small">4.7 (156 review)</span>
                                </div>
                                <div class="product-price mb-3">
                                    Rp 65.000
                                </div>
                                <button class="btn btn-add-cart w-100" onclick="addToCart(4, 'Kaos Anak Karakter')">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="fashion" data-price="110000">
                        <div class="card product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <div class="product-badge">Trending</div>
                            </div>
                            <div class="card-body">
                                <h6 class="product-title">Kaos Lengan Panjang Casual</h6>
                                <p class="text-muted small mb-2">Fashion • Long Sleeve</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="small">4.5 (234 review)</span>
                                </div>
                                <div class="product-price mb-3">
                                    Rp 110.000
                                    <span class="old-price ms-2">Rp 125.000</span>
                                </div>
                                <button class="btn btn-add-cart w-100" onclick="addToCart(5, 'Kaos Lengan Panjang')">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4 product-item" data-category="fashion" data-price="85000">
                        <div class="card product-card">
                            <div class="product-image">
                                <i class="fas fa-tshirt"></i>
                                <div class="product-badge">Stok: 15</div>
                            </div>
                            <div class="card-body">
                                <h6 class="product-title">Kaos Vintage Retro Style</h6>
                                <p class="text-muted small mb-2">Fashion • Vintage</p>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="small">4.4 (78 review)</span>
                                </div>
                                <div class="product-price mb-3">
                                    Rp 85.000
                                </div>
                                <button class="btn btn-add-cart w-100" onclick="addToCart(6, 'Kaos Vintage Retro')">
                                    <i class="fas fa-cart-plus me-2"></i>Tambah ke Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                        <li class="page-item active">
                            <a class="page-link" href="#">1</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage(2)">2</a>
                        </li>
                        <li class="page-item">
                            <a class="page-link" href="#" onclick="loadPage(2)">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // Your existing JavaScript code here...
    </script>
</body>
</html>