<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Dapatkan path yang harus dialihkan oleh pengguna ketika mereka tidak terautentikasi.
     * * Kami menyesuaikan ini untuk memastikan permintaan AJAX (Wishlist) mengembalikan NULL (memicu 401 JSON)
     * dan BUKAN pengalihan HTML.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Kondisi: Jika permintaan mengharapkan JSON ATAU secara eksplisit mengirimkan header AJAX
        if ($request->expectsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return null; // Mengembalikan NULL memicu respons JSON 401 Unauthorized
        }

        // Kondisi default: Untuk permintaan browser biasa, alihkan ke halaman login.
        // Ganti 'login' jika nama rute login Anda berbeda (misal: 'login.form')
        return route('login.form'); 
    }
}