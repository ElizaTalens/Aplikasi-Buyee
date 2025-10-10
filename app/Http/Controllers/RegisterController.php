<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        // sesuaikan dengan lokasi file Blade (lihat bagian B)
        return view('pages.register');
    }

    public function register(Request $request)
    {
        // validasi
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // simpan user (role default: user)
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user',
        ]);

        return redirect()->route('login.form')
            ->with('success', 'Registrasi berhasil, silakan login!');
    }
}
