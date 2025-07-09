<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles  // Menerima satu atau lebih role (e.g., 'admin', 'guru')
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect('login');
        }

        // 2. Ambil role dari pengguna yang sedang login
        $userRole = Auth::user()->role;

        // 3. Periksa apakah role pengguna ada di dalam daftar role yang diizinkan
        if (in_array($userRole, $roles)) {
            // Jika cocok, lanjutkan ke halaman yang dituju
            return $next($request);
        }

        // 4. Jika role tidak cocok, kembalikan halaman error 403 (Akses Ditolak)
        abort(403, 'UNAUTHORIZED ACTION.');
    }
}
