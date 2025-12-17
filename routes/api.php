<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;

// ==== Rutas para la entidad Business ====
Route::prefix('business')->group(function () {
    Route::get('/{id}', [BusinessController::class, 'findById'])
    ->whereNumber('id'); // TODO: Preguntar si esta validacion debe ir aquí
    
    Route::post('/', [BusinessController::class, 'create']);

    Route::put('{id}', [BusinessController::class, 'update'])
        ->whereNumber('id');

    Route::delete('{id}', [BusinessController::class, 'delete'])
        ->whereNumber('id');
});