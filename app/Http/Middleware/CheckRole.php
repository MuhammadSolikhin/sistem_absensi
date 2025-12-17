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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Cek apakah user sudah login (seharusnya sudah dihandle auth middleware, tapi double check)
        if (! $request->user()) {
            return redirect('/login');
        }

        // 2. Cek apakah role user sesuai
        if ($request->user()->role !== $role) {
            abort(403, 'AKSES DITOLAK: Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}