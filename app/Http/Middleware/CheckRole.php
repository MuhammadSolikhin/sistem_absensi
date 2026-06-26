<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!$request->user()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user ada di salah satu role yang diizinkan
        // Jika parameter kosong, anggap semua boleh (atau default behavior)
        if (empty($roles)) {
            return $next($request);
        }

        if (!in_array($request->user()->role, $roles)) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}