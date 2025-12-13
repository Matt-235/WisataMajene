<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PengelolaMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'pengelola_wisata') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya pengelola wisata yang dapat mengakses halaman ini.');
        }

        // Cek apakah akun sudah diverifikasi
        if (auth()->user()->status_verifikasi !== 'terverifikasi') {
            return redirect('/')->with('error', 'Akun Anda belum diverifikasi oleh admin.');
        }

        return $next($request);
    }
}