<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalSanctumAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // SOLO intenta autenticar si hay Authorization header
        if ($request->bearerToken()) {
            Auth::shouldUse('sanctum');
            Auth::guard('sanctum')->user(); // fuerza la autenticación
        }

        return $next($request);
    }
}
