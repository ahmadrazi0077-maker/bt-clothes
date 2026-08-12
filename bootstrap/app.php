<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // ✅ CSRF Exception
        $middleware->validateCsrfTokens(except: [
            'cart/*',
            'cart/add',
            'cart/update',
            'cart/remove',
            'newsletter/subscribe',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ✅ Handle errors
        $exceptions->renderable(function (Throwable $e, Request $request) {
            return response()->json([
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ], 500);
        });
    })->create();