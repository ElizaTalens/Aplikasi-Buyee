<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Izinkan admin mengakses rute logout agar bisa keluar dari sesi
        if ($request->routeIs('logout')) {
            return $next($request);
        }

        // Jika user sudah login dan rolenya admin
        if (Auth::check() && Auth::user()->role === 'admin') {
            \Log::info('RedirectIfAdmin middleware: Admin trying to access user page', [
                'user_id' => Auth::user()->id,
                'email' => Auth::user()->email,
                'url' => $request->url()
            ]);

            // Redirect admin ke dashboard admin
            return redirect()->route('admin.page.dashboard')
                ->with('info', 'Anda sudah login sebagai admin. Silakan gunakan dashboard admin.');
        }

        return $next($request);
    }
}
