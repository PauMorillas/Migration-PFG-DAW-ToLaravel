<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BusinessController;

Route::prefix('users')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/{userId}', [UserController::class, 'findById'])
    ->whereNumber('userId');
    Route::put('/{userId}', [UserController::class, 'update'])
    ->whereNumber('userId');
    Route::delete('/{userId}', [UserController::class, 'delete'])
    ->whereNumber('userId');
});

// ==== Rutas para la entidad Business ====
Route::prefix('businesses')->group(function () {
    Route::get('/{id}', [BusinessController::class, 'findById'])
        ->whereNumber('businessId'); // TODO: Preguntar si esta validacion debe ir aquí

    Route::post('/', [BusinessController::class, 'create']);

    Route::put('{id}', [BusinessController::class, 'update'])
        ->whereNumber('id');

    Route::delete('{id}', [BusinessController::class, 'delete'])
        ->whereNumber('id');

    // === Rutas de servicios (1-N Desde negocio) ===
    Route::get('{id}/services', [ServiceController::class, 'findAll']);
    Route::get('{id}/services/{serviceId}', [ServiceController::class, 'findById'])
        ->whereNumber('id');
    // TODO: CAMBIAR NAMING A ASI, PARA SER MÁS EXPLICITO
    Route::post('{businessId}/services/', [ServiceController::class, 'create']);

    Route::put('{id}/services/{serviceId}', [ServiceController::class, 'update'])
        ->whereNumber('id');

    Route::delete('{id}/services/{serviceId}', [ServiceController::class, 'delete'])
        ->whereNumber('id');
});
// TODO: Autenticación con Sanctum, ->middleware('auth:sanctum')