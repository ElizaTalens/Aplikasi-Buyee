# Buyee - Marketplace & E-Commerce Platform

Buyee adalah platform e-commerce berbasis web yang menyediakan pengalaman belanja online yang lengkap, mulai dari penjelajahan produk hingga proses checkout. Proyek ini dibangun dengan fokus pada kemudahan akses menggunakan integrasi media sosial dan manajemen data yang terstruktur.

## 🌟 Fitur Utama

* **Autentikasi Google**: Integrasi Laravel Socialite yang memungkinkan pengguna untuk masuk atau mendaftar dengan cepat menggunakan akun Google.
* **Katalog & Pencarian Produk**: Pengguna dapat mencari dan memfilter produk berdasarkan kategori untuk menemukan barang yang diinginkan dengan mudah.
* **Sistem Keranjang & Wishlist**: Fitur untuk menyimpan produk yang ingin dibeli segera di keranjang atau menyimpannya untuk nanti di daftar keinginan (wishlist).
* **Proses Checkout**: Alur pembelian yang terintegrasi mencakup pengisian alamat pengiriman dan ringkasan pesanan.
* **Riwayat Pesanan**: Pengguna dapat memantau status dan melihat kembali detail transaksi yang telah dilakukan.
* **Dashboard Admin**: Panel kendali bagi administrator untuk mengelola produk, kategori, dan memantau pesanan yang masuk.
* **Manajemen Profil**: Fitur bagi pengguna untuk memperbarui informasi pribadi dan alamat mereka.

## 🚀 Teknologi yang Digunakan

* **Laravel 11**: Framework PHP modern yang digunakan sebagai fondasi utama aplikasi.
* **Tailwind CSS**: Digunakan untuk perancangan antarmuka pengguna yang responsif dan modern.
* **Vite**: Alat build frontend untuk manajemen aset JavaScript dan CSS yang lebih cepat.
* **Laravel Socialite**: Untuk menangani autentikasi pihak ketiga melalui Google OAuth.
* **MySQL**: Sistem manajemen basis data relasional untuk menyimpan data produk, pengguna, dan transaksi.

## 📋 Prasyarat Instalasi

Pastikan lingkungan pengembangan Anda memiliki:

* PHP >= 8.2
* Composer
* Node.js & NPM
* Database MySQL

## 📂 Susunan Project

Struktur folder utama dalam proyek ini mencakup:

```text
app/
├── Http/Controllers/    # Kontroler untuk logika bisnis (Cart, Checkout, Admin, dll)
├── Models/              # Model Eloquent (Product, Order, User, Wishlist)
└── Http/Middleware/     # Proteksi rute (Admin check, Auth)
resources/
├── views/               # Template Blade untuk UI (Pages, Layouts)
└── js/ & css/           # Aset frontend dengan Tailwind
routes/
└── web.php              # Definisi rute aplikasi web
database/
├── migrations/          # Skema tabel database (Users, Products, Orders)
└── seeders/             # Data awal untuk pengujian (Admin, Products)

```

## 🛠️ Instalasi dan Penggunaan

1. **Clone repositori**:
```bash
git clone [https://github.com/username/aplikasi-buyee.git](https://github.com/username/aplikasi-buyee.git)
cd aplikasi-buyee

```


2. **Instal dependensi**:
```bash
composer install
npm install

```


3. **Konfigurasi Environment**:
Salin file `.env.example` menjadi `.env` dan konfigurasikan database serta kredensial Google Client ID/Secret.
```bash
cp .env.example .env
php artisan key:generate

```


4. **Setup Database**:
Jalankan migrasi dan seeder untuk mendapatkan data awal:
```bash
php artisan migrate --seed

```


5. **Jalankan Aplikasi**:
Buka dua terminal dan jalankan perintah berikut:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev

```



## 🤝 Kontribusi

Kami sangat terbuka untuk kontribusi!

1. Fork proyek ini.
2. Buat branch fitur baru (`git checkout -b fitur/FiturBaru`).
3. Commit perubahan Anda (`git commit -m 'Menambahkan Fitur Baru'`).
4. Push ke branch tersebut (`git push origin fitur/FiturBaru`).
5. Buat Pull Request.

## 📄 Lisensi

Proyek ini dilisensikan di bawah **Lisensi MIT**. Anda bebas untuk menggunakan dan memodifikasi proyek ini sesuai dengan ketentuan lisensi.

```

```
