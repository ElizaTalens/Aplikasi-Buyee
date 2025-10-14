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
            
            // Ambil user yang baru login
            $user = $request->user();
            
            // Log untuk debugging
            \Log::info('User login berhasil', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'intended_role' => $request->role
            ]);

            // Pastikan role user sesuai dengan role yang dipilih di form
            if ($user->role !== $request->role) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'Role yang dipilih tidak sesuai dengan akun Anda.',
                ])->onlyInput('email', 'role');
            }

            // Redirect berdasarkan role dengan pengecekan yang lebih ketat
            if ($user->role === 'admin') {
                \Log::info('Admin login - redirecting to dashboard', ['user_id' => $user->id]);
                return redirect()->intended(route('admin.page.dashboard'));
            } else if ($user->role === 'user') {
                \Log::info('User login - redirecting to home', ['user_id' => $user->id]);
                return redirect()->intended(route('home'));
            } else {
                // Jika role tidak dikenali, logout dan error
                Auth::logout();
                return back()->withErrors([
                    'role' => 'Role tidak valid.',
                ])->onlyInput('email', 'role');
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

        return redirect()->route('login');
    }
}
