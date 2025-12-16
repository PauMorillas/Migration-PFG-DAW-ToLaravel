<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;

// ==== Business routes ====
Route::get('/business/{id}', [BusinessController::class, 'findById'])
    ->whereNumber('id');
