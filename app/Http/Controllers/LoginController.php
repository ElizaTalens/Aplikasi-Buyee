<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // tampilkan form login (sesuai web.php -> 'pages.login')
    public function showLoginForm()
    {
        return view('pages.login');
    }

    public function login(Request $request)
    {
        // Validasi input (role wajib, email harus format email)
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        // Coba login dengan credentials + role, sertakan remember (checkbox)
        $remember    = $request->boolean('remember');
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ];

        // di dalam metode login()
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            if ($request->user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } else { // Ini akan menangani role 'user'
                return redirect()->route('home');
            }
        }


        // Gagal login
        return back()->withErrors([
            'email' => 'Email, password, atau role tidak sesuai.',
        ])->onlyInput('email', 'role');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}
