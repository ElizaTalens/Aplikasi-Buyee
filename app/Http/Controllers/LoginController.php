<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // tampilkan form login 
    public function showLoginForm(Request $request)
    {
        $redirect = $request->query('redirect');
        if ($redirect) {
            // ambil path dan tolak jika mengarah ke endpoint API/JSON atau route khusus yg hanya mengembalikan data
            $path = parse_url($redirect, PHP_URL_PATH) ?? '';
            $blockedPatterns = [
                '#/api/#i',
                '#/wishlist/count#i',
                '#/.*\.json$#i',
                '#/ajax/#i'
            ];
            $isBlocked = false;
            foreach ($blockedPatterns as $p) {
                if (preg_match($p, $path)) { $isBlocked = true; break; }
            }
            if (! $isBlocked) {
                session(['redirect_url' => $redirect]);
            } else {
                session()->forget('redirect_url');
            }
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

            // Pastikan redirect aman sebelum dipakai
            if (session()->has('redirect_url')) {
                $redirect = session('redirect_url');
                $path = parse_url($redirect, PHP_URL_PATH) ?? '';
                // ulangi cek block supaya tidak mengarahkan ke JSON/API
                if (preg_match('#(/api/|/wishlist/count|\.json|/ajax/)#i', $path)) {
                    $redirect = route('home');
                }
                session()->forget('redirect_url');
                return redirect($redirect);
            }

            // default redirect
            if ($user->role === 'admin') {
                return redirect()->intended(route('admin.page.dashboard'));
            } else {
                return redirect()->intended(route('home'));
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
