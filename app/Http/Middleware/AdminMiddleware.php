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

        return $next($request);
    }
}
