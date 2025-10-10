<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Buyee</title>
    
    @vite(['resources/css/app.css','resources/js/app.js'])
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .primary-pink { color: #f8a9c2; }
        .bg-primary-pink { background-color: #f8a9c2; }
        .border-primary-pink { border-color: #f8a9c2; }
        .menu-item:hover { background-color: #fef7f0; }
    </style>
</head>
<body class="bg-gray-50">

    @include('layouts.navbar')

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        {{-- BREADCRUMBS DITAMBAHKAN DI SINI --}}
        <nav class="pt-20 text-sm text-gray-500">
            <ol class="flex items-center gap-3">
                <li><a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-900">Home</a></li>
                <li class="text-gray-300">›</li>
                <li class="text-gray-900">Profil Saya</li>
            </ol>
        </nav>

        {{-- Margin atas diubah menjadi mt-4 karena pt-30 sudah ada di atas --}}
        <div class="mt-4 grid grid-cols-12 gap-6">
            
            <aside class="col-span-3">
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex items-center mb-4">
                        <img src="{{ $user->profile_photo_url ?? 'https://via.placeholder.com/60x60/f8a9c2/ffffff?text=' . strtoupper(substr($user->name, 0, 1)) }}" 
                             alt="{{ $user->name }}" class="w-16 h-16 rounded-full mr-4 object-cover">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">Member</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <i class="fas fa-wallet text-blue-500 text-2xl w-8 text-center mr-3"></i>
                            <span class="text-gray-700">GoPay</span>
                        </div>
                        <span class="font-semibold">Rp{{ number_format($walletData['gopay']) }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-coins text-yellow-500 text-2xl w-8 text-center mr-3"></i>
                            <span class="text-gray-700">Saldo</span>
                        </div>
                        <span class="font-semibold">Rp{{ number_format($walletData['saldo']) }}</span>
                    </div>
                </div>

                <nav class="bg-white rounded-lg shadow-sm">
                    <div class="p-4 border-b">
                        <span class="font-semibold text-gray-900">Pembelian</span>
                        <div class="mt-4 space-y-2">
                            <a href="{{-- route('transactions') --}}" class="block text-gray-700 menu-item p-2 rounded">Daftar Transaksi</a>
                        </div>
                    </div>
                    <div class="p-4">
                        <span class="font-semibold text-gray-900">Profil Saya</span>
                        <div class="mt-4 space-y-2">
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Wishlist</a>
                            <a href="#" class="block text-gray-700 menu-item p-2 rounded">Atur Profil</a>
                        </div>
                    </div>
                </nav>
            </aside>

            <main class="col-span-9">
                <div class="bg-white rounded-t-lg shadow-sm">
                    <div class="border-b">
                        <nav class="flex space-x-8 px-6">
                            <button class="py-4 relative primary-pink font-medium">
                                Biodata Diri
                                <span class="absolute bottom-2 left-0 w-full h-0.5 bg-primary-pink"></span>
                            </button>
                            <button class="py-4 text-gray-500 hover:text-gray-700 font-medium">Daftar Alamat</button>
                        </nav>
                    </div>
                </div>

                <div class="bg-white rounded-b-lg shadow-sm p-6">
                    <div class="grid grid-cols-12 gap-6">
                        <div class="col-span-4">
                            <div class="text-center">
                                <img src="{{ $user->profile_photo_url ?? 'https://via.placeholder.com/200x250/f8a9c2/ffffff?text=' . strtoupper(substr($user->name, 0, 1)) }}" 
                                     alt="Profile Photo" class="w-48 h-60 object-cover rounded-lg mx-auto mb-4 shadow-md">
                                <button class="w-full py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Pilih Foto</button>
                            </div>
                        </div>

                        <div class="col-span-8">
                            <h2 class="text-2xl font-bold text-gray-900 mb-6">Biodata Diri</h2>
                            <div class="space-y-6">
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Nama</label>
                                    <div class="col-span-2"><span class="text-gray-900">{{ $user->name }}</span></div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Tanggal Lahir</label>
                                    <div class="col-span-2"><span class="text-gray-900">{{ $user->birth_date ? \Carbon\Carbon::parse($user->birth_date)->format('d F Y') : 'Belum diatur' }}</span></div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Jenis Kelamin</label>
                                    <div class="col-span-2"><span class="text-gray-900">{{ $user->gender ? ucfirst($user->gender) : 'Belum diatur' }}</span></div>
                                </div>
                                <hr class="my-6">
                                <h3 class="text-2xl font-bold text-gray-900">Kontak</h3>
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Email</label>
                                    <div class="col-span-2"><span class="text-gray-900">{{ $user->email }}</span></div>
                                </div>
                                <div class="grid grid-cols-3 gap-4 items-center">
                                    <label class="text-gray-700 font-medium">Nomor HP</label>
                                    <div class="col-span-2"><span class="text-gray-900">{{ $user->phone ?? 'Belum diatur' }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($cartItems->isNotEmpty())
                    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Item Terbaru di Keranjang Anda</h2>
                        <div class="space-y-4">
                            @foreach ($cartItems as $item)
                                <div class="flex items-center gap-4">
                                    <img src="{{ $item->product->images[0] ?? asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-lg object-cover">
                                    <div class="flex-grow">
                                        <p class="font-semibold">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $item->quantity }} x Rp{{ number_format($item->price) }}</p>
                                    </div>
                                    <p class="font-semibold">Rp{{ number_format($item->quantity * $item->price) }}</p>
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-6">
                        <dl class="space-y-2">
                            <div class="flex items-center justify-between">
                                <dt class="text-gray-600">Subtotal ({{ $cartItems->sum('quantity') }} item)</dt>
                                <dd class="text-lg font-semibold">Rp{{ number_format($total, 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                        <a href="{{ route('cart.index') }}" class="mt-6 block w-full text-center rounded-md bg-primary-pink py-2 text-sm font-semibold text-white hover:opacity-90 transition-opacity">
                            Lihat Keranjang
                        </a>
                    </div>
                @endif
            </main>

        </div>
    </main>

    @include('layouts.footer')
</body>
</html>