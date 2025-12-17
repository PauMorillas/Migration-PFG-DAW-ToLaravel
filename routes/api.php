<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BusinessController;

// ==== Rutas para la entidad Business ====
Route::prefix('businesses')->group(function () {
    Route::get('/{id}', [BusinessController::class, 'findById'])
    ->whereNumber('id'); // TODO: Preguntar si esta validacion debe ir aquí
    
    Route::post('/', [BusinessController::class, 'create']);

    Route::put('{id}', [BusinessController::class, 'update'])
        ->whereNumber('id');

    Route::delete('{id}', [BusinessController::class, 'delete'])
        ->whereNumber('id');

    // === Rutas de servicios (1-N Desde negocio) ===
    Route::post('{businessId}/services', [ServiceController::class, 'findAllByNegocioId']); // TODO IMPLEMENTAR EL METODO EN EL SERVICE Y CONTROLLER
    Route::get('{businessId}/services/{id}' , [ServiceController::class, 'findById'])
    ->whereNumber('id');

    Route::post('{businessId}/services/{id}', [ServiceController::class, 'create']);

    Route::put('{businessId}/services/{serviceId}', [ServiceController::class, 'update'])
        ->whereNumber('id');

    Route::delete('{businessId}/services/{serviceId}', [ServiceController::class, 'delete'])
        ->whereNumber('id');
});