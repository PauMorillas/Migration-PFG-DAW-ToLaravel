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
        // Le pedimos al guard de Sanctum que compruebe si hay una sesión activa.
        // Sanctum buscará automáticamente la cookie HttpOnly o un Bearer token.
        if (Auth::guard('sanctum')->check()) {
            // Si hay usuario (por cookie o token), le decimos a Laravel que use este guard
            // para el resto de la petición, así Auth::user() no devolverá null.
            Auth::shouldUse('sanctum');
        }

        return $next($request);
    }
}

//class OptionalSanctumAuth
//{
//    public function handle(Request $request, Closure $next): Response
//    {
//        // SOLO intenta autenticar si hay Authorization header
//        if ($request->bearerToken()) {
//            Auth::shouldUse('sanctum');
//            Auth::guard('sanctum')->user(); // fuerza la autenticación
//        }
//
//        return $next($request);
//    }
//}
