<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
<<<<<<< HEAD
use App\Models\User;
use Illuminate\Support\Facades\Hash;
=======
use Illuminate\Support\Facades\Hash;
use App\Models\User;
>>>>>>> semua-halaman

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
<<<<<<< HEAD
        return view('register'); // nama blade kamu
=======
        // sesuaikan dengan lokasi file Blade (lihat bagian B)
        return view('pages.register');
>>>>>>> semua-halaman
    }

    public function register(Request $request)
    {
        // validasi
        $request->validate([
<<<<<<< HEAD
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // simpan ke database
        User::create([
            'name' => $request->name, // kalau ada kolom name
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // set role default ke 'user'
        ]);

        // redirect setelah sukses
        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login!');
=======
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

        return redirect()->route('login')
            ->with('success', 'Registrasi berhasil, silakan login!');
>>>>>>> semua-halaman
    }
}
