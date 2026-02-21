<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah sudah login DAN rolenya adalah super_admin
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request);
        }

        // Kalau bukan super_admin, tendang balik ke halaman depan dengan pesan error 403
        abort(403, 'Akses Ditolak. Halaman ini khusus Super Admin.');
    }
}