<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OptionalSanctumAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Fuerza a Sanctum a intentar autenticar
        auth('sanctum')->check();

        return $next($request);
    }
}
