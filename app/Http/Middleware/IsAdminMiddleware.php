<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek jika user sudah login
        if (!Auth::check()) {
            Log::warning('Admin middleware: User not authenticated', [
                'url' => $request->url(),
                'ip' => $request->ip()
            ]);
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::user();
        
        // Cek jika perannya adalah 'admin'
        if ($user->role === 'admin') {
            Log::info('Admin middleware: Access granted', [
                'user_id' => $user->id,
                'email' => $user->email,
                'url' => $request->url()
            ]);
            // Jika ya, lanjutkan ke halaman yang dituju
            return $next($request);
        }

        // Log akses yang ditolak
        Log::warning('Admin middleware: Access denied', [
            'user_id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'url' => $request->url()
        ]);

        // Jika bukan admin, kembalikan ke halaman utama dengan pesan error
        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses admin.');
    }
}