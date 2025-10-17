<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // login.blade.php
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string', // Gunakan string untuk mencegah masalah validasi
            'role' => 'required|in:admin,user', 
        ]);
        
        // Tambahkan role ke credentials untuk proses attempt
        $credentials['role'] = $credentials['role'];

        // 2. Proses Login Menggunakan Auth::attempt()
        // Auth::attempt() menangani pencarian user dan pengecekan password secara aman
        // Note: Kita TIDAK perlu menggunakan User::where() dan Hash::check() manual
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) { // Tambahkan opsi 'remember' jika ada
            $request->session()->regenerate();

            $user = Auth::user(); // Ambil user yang baru login

            // 3. Logika Pengalihan Cerdas (Intended URL)
            
            // Tentukan rute default berdasarkan role
            if ($user->role === 'admin') {
                $defaultRoute = '/buyee_admin_dashboard';
            } else { // role === 'user'
                $defaultRoute = route('home');
            }
            
            // Gunakan intended() untuk mengarahkan ke URL yang terakhir dicoba (misal: /product/tas-cantik)
            // Jika tidak ada intended URL, arahkan ke rute default role yang sudah ditentukan.
            return redirect()->intended($defaultRoute);

        }

        // 4. Jika Gagal: Kembalikan dengan error
        // Gunakan ValidationException untuk penanganan error yang lebih bersih di Laravel
        throw ValidationException::withMessages([
            'email' => ['Email, password, atau role salah.'],
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        // Invalidate session dan regenerate token untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Mengarahkan ke halaman utama (rute '/') yang dinamai 'home'
        return redirect()->route('home'); 
    }
}