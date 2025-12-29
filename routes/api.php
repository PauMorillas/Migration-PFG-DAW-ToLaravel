<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BookingController;

Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
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

    // === Rutas de Reservas (1-N desde Servicio) ===
    Route::get('{businessId}/services/{serviceId}/bookings',[BookingController::class, 'findAll'])->whereNumber(['businessId', 'serviceId']);
    Route::get('{businessId}/services/{serviceId}/bookings/{bookingId}',[BookingController::class, 'findById'])->whereNumber(['businessId', 'serviceId', 'bookingId']);
    Route::post('{businessId}/services/{serviceId}/bookings',[BookingController::class, 'create'])->whereNumber(['businessId', 'serviceId']);
    Route::delete('{businessId}/services/{serviceId}/bookings/{bookingId}',[BookingController::class, 'delete'])->whereNumber(['businessId', 'serviceId', 'bookingId']);
    Route::put('{businessId}/services/{serviceId}/bookings/{bookingId}',[BookingController::class, 'update'])->whereNumber(['businessId', 'serviceId', 'bookingId']);
});
// TODO: Autenticación con Sanctum, ->middleware('auth:sanctum')
