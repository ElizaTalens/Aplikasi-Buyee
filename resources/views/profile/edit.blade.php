@extends('layouts.master')

@section('title', 'Profil Saya - Buyee')

@section('content')

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20">

    {{-- Breadcrumbs --}}
    <nav class="text-sm text-gray-500 pb-5">
      <ol class="flex items-center gap-3">
        <li><a href="{{ route('home') }}" class="hover:text-gray-900">Home</a></li>
        <li class="text-gray-300">›</li>
        <li class="text-gray-900">Profil Saya</li>
      </ol>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

        {{-- ======================================================= --}}
        {{-- |                KIRI: SIDEBAR NAVIGASI             | --}}
        {{-- ======================================================= --}}
        <aside class="md:col-span-4 lg:col-span-3">
            <div class="space-y-6">
                {{-- Kartu Info Pengguna --}}
                <div class="border border-gray-200 bg-white rounded-xl p-5 flex items-center space-x-4">
                    <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://via.placeholder.com/60x60/2a242b/ffffff?text=' . substr($user->name, 0, 1) }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover bg-gray-200">
                    <div>
                        <h2 class="font-bold text-gray-900 text-lg">{{ $user->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Menu Navigasi --}}
                <div class="border border-gray-200 bg-white rounded-xl p-3">
                    <nav class="space-y-1">
                        {{-- Group Menu: Pembelian --}}
                        <div>
                            <h3 class="px-3 py-2 text-xs font-semibold uppercase text-gray-400 tracking-wider">Pembelian</h3>
                            <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-receipt w-6 text-center mr-2"></i>
                                Daftar Transaksi
                            </a>
                            <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-star w-6 text-center mr-2"></i>
                                Ulasan
                            </a>
                        </div>

                        {{-- Group Menu: Pengaturan Akun --}}
                        <div class="pt-2">
                            <h3 class="px-3 py-2 text-xs font-semibold uppercase text-gray-400 tracking-wider">Pengaturan Akun</h3>
                             <a href="{{ route('profile.edit') }}" class="flex items-center px-3 py-2.5 text-sm font-bold text-white bg-gray-900 rounded-lg">
                                <i class="fa-solid fa-user-pen w-6 text-center mr-2"></i>
                                Biodata Diri
                            </a>
                            <a href="#" class="flex items-center px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-map-location-dot w-6 text-center mr-2"></i>
                                Daftar Alamat
                            </a>
                        </div>
                        
                        {{-- Tombol Logout --}}
                        <div class="pt-4">
                            <a href="{{ route('logout') }}" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="flex items-center px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-arrow-right-from-bracket w-6 text-center mr-2"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                        </div>
                    </nav>
                </div>
            </div>
        </aside>

        {{-- ======================================================= --}}
        {{-- |             KANAN: KONTEN UTAMA (FORM)            | --}}
        {{-- ======================================================= --}}
        <div class="md:col-span-8 lg:col-span-9">
            <div class="border border-gray-200 bg-white rounded-xl">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900">Pengaturan Profil</h1>
                    <p class="mt-1 text-gray-500">Ubah informasi pribadi dan detail kontak Anda di sini.</p>
                    
                    @if (session('status') === 'profile-updated')
                        <div class="mt-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm font-semibold" role="alert">
                            Profil berhasil diperbarui!
                        </div>
                    @endif
                </div>
                
                <hr class="border-gray-200">

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                            {{-- Kolom Foto Profil --}}
                            <div class="lg:col-span-1">
                                <h3 class="font-semibold text-gray-900">Foto Profil</h3>
                                <p class="text-sm text-gray-500 mt-1">Ukuran maks. 5MB. Format JPG, PNG.</p>
                                <div class="mt-4 text-center">
                                    <img id="photoPreview" src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://via.placeholder.com/200x250/e5e7eb/374151?text=Photo' }}" alt="Profile Photo" class="w-48 h-60 object-cover rounded-lg mx-auto mb-4 ring-1 ring-gray-200 bg-gray-200">
                                    
                                    <input type="file" name="photo" id="photo" class="hidden" accept="image/png, image/jpeg, image/jpg">
                                    
                                    <button type="button" id="selectPhotoButton" class="w-full py-2.5 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50 transition duration-200">
                                        Pilih Foto
                                    </button>
                                    @error('photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            {{-- Kolom Form Biodata --}}
                            <div class="lg:col-span-2 space-y-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm">
                                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Lahir</label>
                                    <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date', optional($user->birth_date)->format('Y-m-d')) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm">
                                    @error('birth_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-1.5">Jenis Kelamin</label>
                                    <select id="gender" name="gender" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="female" @selected(old('gender', $user->gender) == 'female')>Wanita</option>
                                        <option value="male" @selected(old('gender', $user->gender) == 'male')>Pria</option>
                                    </select>
                                    @error('gender') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                                
                                <hr>
                                <h3 class="text-lg font-semibold text-gray-900 -mt-2 mb-2">Detail Kontak</h3>
                                
                                <div>
                                     <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                    <input type="email" id="email" name="email" value="{{ $user->email }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm bg-gray-100 cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor HP</label>
                                    <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-900 focus:ring-gray-900 sm:text-sm">
                                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 px-6 py-4 rounded-b-xl text-right">
                        <button type="submit" class="inline-flex justify-center rounded-lg border border-transparent bg-gray-900 py-2.5 px-6 text-sm font-semibold text-white shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPhotoButton = document.getElementById('selectPhotoButton');
        const photoInput = document.getElementById('photo');
        const photoPreview = document.getElementById('photoPreview');

        // Saat tombol 'Pilih Foto' diklik, picu event 'click' pada input file yang tersembunyi
        selectPhotoButton.addEventListener('click', function() {
            photoInput.click();
        });

        // Saat file dipilih (di dalam input tersembunyi), tampilkan preview-nya
        photoInput.addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoPreview.src = e.target.result;
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    });
</script>
@endpush