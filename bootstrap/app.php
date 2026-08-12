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
        // ✅ Web middleware
        $middleware->web(append: [
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        ]);
        
        // ✅ CSRF EXCEPTION - Add all cart routes
        $middleware->validateCsrfTokens(except: [
            'cart/add',
            'cart/update',
            'cart/remove',
            'cart/clear',
            'cart/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();