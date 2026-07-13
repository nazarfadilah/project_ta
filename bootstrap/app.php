<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'tamu' => \App\Http\Middleware\TamuMiddleware::class,
        ]);

        // Register global exception middleware inside 'web' group to ensure session and auth are active
        $middleware->appendToGroup('web', \App\Http\Middleware\GlobalExceptionMiddleware::class);

        // Exclude logout routes from CSRF checking to guarantee user can logout after 419 CSRF mismatch
        $middleware->validateCsrfTokens(except: [
            'logout',
            'admin/logout',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Fallback exception handling for Token Mismatch
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            return response()->view('errors.custom', [
                'status' => 419,
                'message' => 'Sesi Anda telah kedaluwarsa atau token CSRF tidak cocok. Silakan coba kembali.'
            ], 419);
        });

        // Fallback exception handling for 404 Route Not Found
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            return response()->view('errors.custom', [
                'status' => '403/404',
                'message' => 'Halaman yang Anda cari tidak ditemukan atau Anda tidak memiliki akses ke halaman ini.'
            ], 404);
        });

        // Fallback exception handling for 403 Access Denied
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            return response()->view('errors.custom', [
                'status' => '403/404',
                'message' => 'Halaman yang Anda cari tidak ditemukan atau Anda tidak memiliki akses ke halaman ini.'
            ], 403);
        });
    })->create();
