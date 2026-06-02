<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class TamuMiddleware
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
        
        // Role 4: Tamu (Users)
        if ($user->roleId !== 4) {
            Auth::guard('web')->logout();
            return redirect()->route('login')->withErrors(['login' => 'Anda tidak memiliki hak akses ke halaman Pengguna.']);
        }

        return $next($request);
    }
}
