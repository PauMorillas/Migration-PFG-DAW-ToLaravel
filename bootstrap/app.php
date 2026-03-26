<?php

use App\Http\Middleware\HandleAppearance;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\OptionalSanctumAuth;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php', // Añadidas las rutas de API
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Excluimos las rutas públicas de la protección CSRF
        $middleware->validateCsrfTokens(except: [
            'api/public/users/register',
            'api/public/users/login',
        ]);

        $middleware->encryptCookies(except: ['appearance', 'sidebar_state']);

        $middleware->alias([
            'auth.optional' => OptionalSanctumAuth::class,
        ]);


        $middleware->web(append: [
            HandleAppearance::class,
            HandleInertiaRequests::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // Esto le dice a Laravel que las rutas de 'api.php' pueden recibir
        // cookies de sesión de los dominios definidos en SANCTUM_STATEFUL_DOMAINS
        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {

    })->create();
