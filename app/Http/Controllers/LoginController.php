<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login'); // login.blade.php
    }

    public function login(Request $request)
    {
        // validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user', 
        ]);

        // cari user berdasarkan email
        $user = User::where('email', $request->email)
            ->where('role', $request->role)
            ->first();

        // cek user & password
        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
        
            if ($user->role === 'admin') {
                return redirect('/buyee_admin_dashboard');
            } elseif ($user->role === 'user') {
                return redirect()->route('home'); // arahkan ke dashboard user
            }
        }

        // kalau gagal
        return back()->withErrors([
            'email' => 'Email atau password, atu role salah',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        // Mengarahkan ke halaman utama (rute '/') yang dinamai 'home'
        return redirect()->route('home'); 
    }
}
