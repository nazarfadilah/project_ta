<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

class GlobalExceptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $response = $next($request);

            // Intercept standard HTTP error status codes
            if ($response->getStatusCode() === 404) {
                return response()->view('errors.custom', [
                    'status' => 404,
                    'message' => 'Halaman yang Anda cari tidak ditemukan.'
                ], 404);
            }

            if ($response->getStatusCode() === 403) {
                return response()->view('errors.custom', [
                    'status' => 403,
                    'message' => 'Anda tidak memiliki hak akses untuk mengakses halaman ini.'
                ], 403);
            }

            return $response;
        } catch (TokenMismatchException $e) {
            return response()->view('errors.custom', [
                'status' => 419,
                'message' => 'Sesi Anda telah kedaluwarsa atau token CSRF tidak cocok. Silakan coba kembali.'
            ], 419);
        } catch (NotFoundHttpException $e) {
            return response()->view('errors.custom', [
                'status' => 404,
                'message' => 'Halaman yang Anda cari tidak ditemukan.'
            ], 404);
        } catch (AccessDeniedHttpException $e) {
            return response()->view('errors.custom', [
                'status' => 403,
                'message' => 'Anda tidak memiliki hak akses untuk mengakses halaman ini.'
            ], 403);
        } catch (AuthorizationException $e) {
            return response()->view('errors.custom', [
                'status' => 403,
                'message' => 'Anda tidak memiliki hak akses untuk mengakses halaman ini.'
            ], 403);
        } catch (Throwable $e) {
            // For 500 Internal Server Error
            \Log::error('GlobalExceptionMiddleware caught exception: ' . $e->getMessage(), [
                'exception' => $e
            ]);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'message' => 'Terjadi kesalahan internal pada server.'
                ], 500);
            }

            // Redirect back if referer exists and is not the current page to prevent redirect loops
            $previousUrl = url()->previous();
            if ($previousUrl && $previousUrl !== url()->current()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan internal pada server (' . $e->getMessage() . '). Kembali ke halaman sebelumnya.');
            }

            // If no previous page is available, show the custom 500 page
            return response()->view('errors.custom', [
                'status' => 500,
                'message' => 'Terjadi kesalahan internal pada server. Silakan coba kembali beberapa saat lagi.'
            ], 500);
        }
    }
}
