<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log; 

class LoginController extends Controller
{
    // tampilkan form login 
    public function showLoginForm(Request $request)
    {
        if ($request->has('redirect')) {
            session(['redirect_url' => $request->redirect]);
        }
        return view('pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
            'role'     => 'required|in:admin,user',
        ]);

        $remember    = $request->boolean('remember');
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ];

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
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

            // Cek redirect URL dari session
            if (session()->has('redirect_url')) {
                $redirect = session('redirect_url');
                session()->forget('redirect_url'); // Hapus dari session
                return redirect($redirect);
            }

            // Default redirect berdasarkan role
            if ($user->role === 'admin') {
                \Log::info('Admin login - redirecting to dashboard', ['user_id' => $user->id]);
                return redirect()->intended(route('admin.page.dashboard'));
            } else if ($user->role === 'user') {
                \Log::info('User login - redirecting to home', ['user_id' => $user->id]);
                return redirect()->intended(route('home'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'Role tidak valid.',
                ])->onlyInput('email', 'role');
            }
        }
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