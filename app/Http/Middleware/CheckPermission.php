<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        // TODO: Implementasi permission check untuk user dan role lainnya
        // Contoh penggunaan di routes:
        // Route::middleware(['auth:web', 'permission:create-user,edit-user'])->group(...)
        
        // Untuk saat ini, middleware dimatikan - fokus admin terlebih dahulu
        // Admin dapat akses semua tanpa permission check
        
        return $next($request);
    }
}
