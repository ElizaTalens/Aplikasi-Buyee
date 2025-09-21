<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('register'); // nama blade kamu
    }

    public function register(Request $request)
    {
        // validasi
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // simpan ke database
        User::create([
            'name' => $request->name ?? 'User', // kalau ada kolom name
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // redirect setelah sukses
        return redirect('/login')->with('success', 'Registrasi berhasil, silakan login!');
    }
}
