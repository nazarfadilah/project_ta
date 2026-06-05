<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = Auth::guard('web')->user();
        
        // Role 1: Admin, 2: Pimpinan, 3: Petugas
        if (!in_array($user->roleId, [1, 2, 3])) {
            Auth::guard('web')->logout();
            return redirect()->route('login')->withErrors(['login' => 'Anda tidak memiliki hak akses ke halaman Administrator.']);
        }

        $routeName = $request->route() ? $request->route()->getName() : '';
        $method = $request->method();

        // 1. Pimpinan (Role 2) - Hanya bisa melihat (GET) dan melihat Laporan, tidak bisa kelola/akses Landing Page dan Users
        if ($user->roleId == 2) {
            // Laporan diperbolehkan penuh
            if (str_starts_with($routeName, 'main.laporan.')) {
                return $next($request);
            }
            
            // Dashboard diperbolehkan
            if ($routeName === 'admin.dashboard') {
                return $next($request);
            }

            // Halaman Landing Page dan Kelola Pengguna dilarang penuh untuk Pimpinan
            if (str_starts_with($routeName, 'main.landing.') || str_starts_with($routeName, 'main.users.')) {
                abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk melihat modul ini.');
            }

            // Untuk modul lain, pimpinan hanya diperbolehkan melakukan GET (read-only)
            if ($method !== 'GET') {
                abort(403, 'Akses ditolak. Pimpinan hanya dapat melihat data.');
            }
            
            // Hanya izinkan rute index atau show/details
            $allowedPatterns = [
                'main.tamu.index', 'main.tamu.show',
                'main.gedung.index', 'main.gedung.show',
                'main.ruangan.index', 'main.ruangan.show',
                'main.sarana.index', 'main.sarana.show',
                'main.paket_ruangan.index', 'main.paket_ruangan.show',
                'main.transaksi.peminjaman.index', 'main.transaksi.peminjaman.show',
                'main.peminjaman_sarana.index', 'main.peminjaman_sarana.show',
                'main.berita.index', 'main.berita.show',
            ];

            $isAllowed = false;
            foreach ($allowedPatterns as $pattern) {
                if ($routeName === $pattern || str_starts_with($routeName, $pattern)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed && !empty($routeName)) {
                abort(403, 'Akses ditolak. Rute tidak diizinkan untuk Pimpinan.');
            }
        }

        // 2. Admin (Role 1) - Tidak boleh akses Data Master (Gedung, Ruangan, Sarana, Paket Ruangan) & Peminjaman/Transaksi
        if ($user->roleId == 1) {
            if (str_starts_with($routeName, 'main.ruangan.') || 
                str_starts_with($routeName, 'main.sarana.') || 
                str_starts_with($routeName, 'main.paket_ruangan.') ||
                str_starts_with($routeName, 'main.gedung.') ||
                str_starts_with($routeName, 'main.transaksi.peminjaman.') ||
                str_starts_with($routeName, 'main.peminjaman_sarana.')) {
                abort(403, 'Akses ditolak. Admin tidak mengelola data master atau peminjaman.');
            }
        }

        // 3. Petugas (Role 3) - Tidak boleh kelola Users, Landing Page, Laporan, dan tidak boleh publish berita
        if ($user->roleId == 3) {
            if (str_starts_with($routeName, 'main.users.') || 
                str_starts_with($routeName, 'main.landing.') || 
                str_starts_with($routeName, 'main.laporan.')) {
                abort(403, 'Akses ditolak. Petugas tidak memiliki hak akses ke modul ini.');
            }

            // Petugas tidak boleh publish berita (approve/reject)
            if ($routeName === 'main.berita.approve' || $routeName === 'main.berita.reject') {
                abort(403, 'Akses ditolak. Petugas tidak diizinkan menerbitkan berita.');
            }
        }

        return $next($request);
    }
}
