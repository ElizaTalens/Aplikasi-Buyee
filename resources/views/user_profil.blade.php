<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Buyee</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .primary-pink { color: #f8a9c2; }
        .bg-primary-pink { background-color: #f8a9c2; }
        .border-primary-pink { border-color: #f8a9c2; }
        .hover-pink:hover { background-color: #f8a9c2; color: white; }
        .menu-item:hover { background-color: #fef7f0; }
        .gradient-bg { background: linear-gradient(135deg, #f8a9c2 0%, #fce4ec 100%); }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo dan Promo -->
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt text-gray-600 mr-2"></i>
                        <span class="text-sm text-gray-600">Gratis Ongkir + Banyak Promo</span>
                        <span class="text-sm text-gray-500 ml-2">belanja di aplikasi</span>
                        <i class="fas fa-chevron-right text-gray-400 ml-2"></i>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Tentang Buyee</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Pusat Edukasi Seller</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Promo</a>
                    <a href="#" class="text-gray-600 hover:text-gray-900 text-sm">Buyee Care</a>
                </nav>
            </div>
        </div>

        <!-- Main Navigation -->
        <div class="border-t bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="text-3xl font-bold primary-pink">Buyee</div>
                        <button class="ml-8 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg">
                            Kategori
                        </button>
                    </div>

                    <!-- Search Bar -->
                    <div class="flex-1 max-w-2xl mx-8">
                        <div class="relative">
                            <input type="text" placeholder="Cari di Buyee" 
                                   class="w-full px-4 py-3 pr-12 border border-gray-200 rounded-lg focus:ring-2 focus:ring-pink-200 focus:border-transparent">
                            <button class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                <i class="fas fa-search text-gray-400"></i>
                            </button>
                        </div>
                    </div>

                    <!-- User Actions -->
                    <div class="flex items-center space-x-6">
                        <!-- Cart -->
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-2xl text-gray-600"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">28</span>
                        </div>
                        
                        <!-- Notifications -->
                        <div class="relative">
                            <i class="fas fa-bell text-2xl text-gray-600"></i>
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                        </div>

                        <!-- Mail -->
                        <i class="fas fa-envelope text-2xl text-gray-600"></i>

                        <!-- User Profile -->
                        <div class="flex items-center space-x-3">
                            <div class="text-right">
                                <p class="text-gray-600 text-sm">Toko</p>
                            </div>
                            <img src="https://via.placeholder.com/40x40/f8a9c2/ffffff?text=D" 
                                 alt="Dina" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="font-semibold">Dina</p>
                            </div>
                        </div>

                        <!-- Delivery -->
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span>Dikirim ke</span>
                            <strong class="ml-1">Rumah Dina</strong>
                            <i class="fas fa-chevron-down ml-1"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-12 gap-6">
            <!-- Sidebar -->
            <div class="col-span-3">
                <!-- User Profile Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <img src="https://via.placeholder.com/60x60/f8a9c2/ffffff?text=D" 
                             alt="Dina" class="w-15 h-15 rounded-full mr-4">
                        <div>
                            <h3 class="font-semibold text-gray-900">Dina</h3>
                        </div>
                    </div>
                </div>

                <!-- Plus Membership -->
                <div class="bg-gradient-to-r from-green-400 to-green-500 rounded-lg p-4 mb-6 text-white">
                    <div class="flex items-center mb-2">
                        <span class="bg-white text-green-500 px-2 py-1 rounded text-xs font-bold">PLUS</span>
                    </div>
                    <h4 class="font-semibold mb-1">Nikmatin Gratis Ongkir tanpa batas!</h4>
                    <p class="text-sm opacity-90">Min. belanja Rp0, bebas biaya aplikasi-</p>
                </div>

                <!-- Wallet Section -->
                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <!-- GoPay -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-wallet text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">GoPay</span>
                        </div>
                        <span class="font-semibold">Rp9.091</span>
                    </div>
                    <div class="flex justify-between text-sm text-gray-500 mb-4">
                        <span>GoPay Coins</span>
                        <span>Top-Up GoPay</span>
                    </div>

                    <!-- Buyee Card -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded flex items-center justify-center mr-3">
                                <i class="fas fa-credit-card text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Buyee Card</span>
                        </div>
                        <button class="text-green-500 text-sm hover:underline">Daftar Sekarang</button>
                    </div>

                    <!-- Saldo -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-500 rounded flex items-center justify-center mr-3">
                                <i class="fas fa-coins text-white text-sm"></i>
                            </div>
                            <span class="text-gray-700">Saldo</span>
                        </div>
                        <span class="font-semibold">Rp300</span>
                    </div>
                </div>

                <!-- Menu -->
                <div class="bg-white rounded-lg shadow-sm">
                    <!-- Kotak Masuk -->
                    <div class="p-4 border-b">
                        <button class="flex items-center justify-between w-full text-left">
                            <span class="font-semibold text-gray-900">Kotak Masuk</span>
                            <i class="fas fa-chevron-up text-gray-400"></i>
                        </button>
                        <div class="mt-4 space-y-2">
                            <a href="#" class="flex items-center justify-between menu-item p-2 rounded">
                                <span class="text-gray-700">Chat</span>
                                <span class="bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Ulasan</a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Pesan Bantuan</a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Pesanan Dikomplain</a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Update</a>
                        </div>
                    </div>

                    <!-- Pembelian -->
                    <div class="p-4 border-b">
                        <button class="flex items-center justify-between w-full text-left">
                            <span class="font-semibold text-gray-900">Pembelian</span>
                            <i class="fas fa-chevron-up text-gray-400"></i>
                        </button>
                        <div class="mt-4 space-y-2">
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Menunggu Pembayaran</a>
                            <a href="{{ route('transactions') }}" class="block text-gray-700 menu-item p-2 rounded">Daftar Transaksi</a>
                        </div>
                    </div>

                    <!-- Profil Saya -->
                    <div class="p-4">
                        <button class="flex items-center justify-between w-full text-left">
                            <span class="font-semibold text-gray-900">Profil Saya</span>
                            <i class="fas fa-chevron-up text-gray-400"></i>
                        </button>
                        <div class="mt-4 space-y-2">
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Wishlist</a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Toko Favorit</a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Pengaturan</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Profile Content -->
            <div class="col-span-9">
                <!-- Profile Navigation -->
                <div class="bg-white rounded-t-lg shadow-sm">
                    <div class="border-b">
                        <nav class="flex space-x-8 px-6">
                            <button class="py-4 border-b-2 border-primary-pink primary-pink font-medium">
                                Biodata Diri
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Daftar Alamat
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Pembayaran
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Rekening Bank
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Notifikasi
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Mode Tampilan
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">
                                Keamanan
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="bg-white rounded-b-lg shadow-sm p-6">
                    <div class="grid grid-cols-12 gap-6">
                        <!-- Profile Photo Section -->
                        <div class="col-span-4">
                            <div class="text-center">
                                <img src="https://via.placeholder.com/200x250/f8a9c2/ffffff?text=Dina" 
                                     alt="Profile Photo" 
                                     class="w-48 h-60 object-cover rounded-lg mx-auto mb-4 shadow-md">
                                <button class="w-full py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                                    Pilih Foto
                                </button>
                            </div>
                        </div>

                        <!-- Profile Information -->
                        <div class="col-span-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Ubah Biodata Diri</h2>
                            
                            <div class="space-y-6">
                                <!-- Name -->
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Nama</label>
                                    <div class="col-span-2 flex items-center space-x-4">
                                        <span class="text-gray-900">Dina Amelia</span>
                                        <button class="bg-primary-pink text-white px-4 py-1 rounded-full text-sm hover:opacity-90 transition duration-200">
                                            Ubah
                                        </button>
                                    </div>
                                </div>

                                <!-- Birth Date -->
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Tanggal Lahir</label>
                                    <div class="col-span-2 flex items-center space-x-4">
                                        <span class="text-gray-900">17 Mei 1989</span>
                                        <button class="bg-primary-pink text-white px-4 py-1 rounded-full text-sm hover:opacity-90 transition duration-200">
                                            Ubah Tanggal Lahir
                                        </button>
                                    </div>
                                </div>

                                <!-- Gender -->
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Jenis Kelamin</label>
                                    <div class="col-span-2 flex items-center space-x-4">
                                        <span class="text-gray-900">Wanita</span>
                                        <button class="bg-primary-pink text-white px-4 py-1 rounded-full text-sm hover:opacity-90 transition duration-200">
                                            Ubah
                                        </button>
                                    </div>
                                </div>

                                <hr class="my-6">

                                <!-- Contact Section -->
                                <h3 class="text-xl font-bold text-gray-900 mb-4">Ubah Kontak</h3>

                                <!-- Email -->
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Email</label>
                                    <div class="col-span-2 flex items-center space-x-4">
                                        <span class="text-gray-900">dinaameliapl2@gmail.com</span>
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Terverifikasi
                                        </span>
                                        <button class="bg-primary-pink text-white px-4 py-1 rounded-full text-sm hover:opacity-90 transition duration-200">
                                            Ubah
                                        </button>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Nomor HP</label>
                                    <div class="col-span-2 flex items-center space-x-4">
                                        <span class="text-gray-900">6285641702810</span>
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                            Terverifikasi
                                        </span>
                                        <button class="bg-primary-pink text-white px-4 py-1 rounded-full text-sm hover:opacity-90 transition duration-200">
                                            Ubah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript untuk interaktivitas -->
    <script>
        // Toggle menu accordion
        document.querySelectorAll('button[data-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const content = this.nextElementSibling;
                
                if (content.style.display === 'none' || content.style.display === '') {
                    content.style.display = 'block';
                    icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                } else {
                    content.style.display = 'none';
                    icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                }
            });
        });

        // Tab navigation
        document.querySelectorAll('nav button').forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs
                document.querySelectorAll('nav button').forEach(t => {
                    t.classList.remove('border-primary-pink', 'primary-pink');
                    t.classList.add('text-gray-500');
                });
                
                // Add active class to clicked tab
                this.classList.add('border-primary-pink', 'primary-pink');
                this.classList.remove('text-gray-500');
            });
        });

        // Photo upload simulation
        document.querySelector('button:contains("Pilih Foto")').addEventListener('click', function() {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.click();
        });

        // Edit buttons functionality
        document.querySelectorAll('button:contains("Ubah")').forEach(button => {
            button.addEventListener('click', function() {
                alert('Fitur edit akan membuka modal atau form edit');
            });
        });
    </script>
</body>
</html>