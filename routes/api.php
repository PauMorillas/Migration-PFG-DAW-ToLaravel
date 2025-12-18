<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BusinessController;

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
    Route::get('{id}/services/{serviceId}' , [ServiceController::class, 'findById'])
    ->whereNumber('id');

    Route::post('{id}/services/', [ServiceController::class, 'create']);

    Route::put('{id}/services/{serviceId}', [ServiceController::class, 'update'])
        ->whereNumber('id');

    Route::delete('{id}/services/{serviceId}', [ServiceController::class, 'delete'])
        ->whereNumber('id');
});