<?php

use App\DDD\Infrastructure\EntryPoints\Http\API\Booking\PostController as PreBookingPostController;
use App\DDD\Infrastructure\EntryPoints\Http\API\Booking\PostControllerWithBus as PreBookingPostControllerWithBus;
use App\DDD\Infrastructure\EntryPoints\Http\API\Booking\GetBookingController as PreBookingGetController;
use App\DDD\Infrastructure\EntryPoints\Http\API\Booking\DeleteController as PreBookingDeleteControllerWithBus;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\PreBookingController;
use App\Http\Controllers\BookingController;

// === Rutas del Usuario Públicas (SIN AUTH) ===
Route::prefix('users')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

// ====== Rutas de lectura pública - Negocios y Service ======
// ==== Rutas para la entidad Business ====
Route::prefix('businesses')->group(function () {
    Route::get('/{businessId}', [BusinessController::class, 'findById'])
        ->whereNumber('businessId'); // TODO: Preguntar si esta validación debe ir aquí

    // === Rutas de Services (1-N Desde negocio) ===
    Route::get('{businessId}/services', [ServiceController::class, 'findAll'])
        ->whereNumber('businessId');
    Route::get('{businessId}/services/{serviceId}', [ServiceController::class, 'findById'])
        ->whereNumber('businessId');

    // === Rutas con Autorización Opcional ===
    Route::middleware('auth.optional')->group(function () {

        // === Rutas de PreBooking (1-N desde Servicio) ===
        Route::get('{businessId}/services/{serviceId}/bookings', [PreBookingController::class, 'findAll'])
            ->whereNumber(['businessId', 'serviceId']);
        /*Route::get('{businessId}/services/{serviceId}/bookings/{bookingId}', [PreBookingController::class, 'findById'])
            ->whereNumber(['businessId', 'serviceId', 'bookingId']);*/
        /*Route::put('{businessId}/services/{serviceId}/bookings/{bookingId}', [PreBookingController::class, 'update'])
            ->whereNumber(['businessId', 'serviceId', 'bookingId']);*/

        Route::get('{businessId}/services/{serviceId}/bookings/{bookingId}', PreBookingGetController::class)
            ->whereNumber(['businessId', 'serviceId', 'bookingId']);

        // === Rutas de Bookings ===
        Route::prefix('{businessId}/services/{serviceId}/bookings')->group(function () {
            Route::get('{bookingId}/v2', [BookingController::class, 'findById'])
                ->whereNumber(['businessId', 'serviceId', 'bookingId']);
            Route::get('/v2', [BookingController::class, 'findAll'])
                ->whereNumber(['businessId', 'serviceId']);
        });
    });
});

// === Rutas Protegidas (CON AUTH) ===
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me']);

    Route::prefix('users')->group(function () {
        Route::get('/{userId}', [UserController::class, 'findById'])
            ->whereNumber('userId');
        Route::put('/{userId}', [UserController::class, 'update'])
            ->whereNumber('userId');
        Route::delete('/{userId}', [UserController::class, 'delete'])
            ->whereNumber('userId');
    });

    Route::prefix('businesses')->group(function () {
        Route::post('/', [BusinessController::class, 'create']);

        Route::put('{businessId}', [BusinessController::class, 'update'])
            ->whereNumber('businessId');

        Route::delete('{businessId}', [BusinessController::class, 'delete'])
            ->whereNumber('businessId');
        Route::post('{businessId}/services/', [ServiceController::class, 'create'])
            ->whereNumber('businessId');

        Route::put('{businessId}/services/{serviceId}', [ServiceController::class, 'update'])
            ->whereNumber('businessId');

        Route::delete('{businessId}/services/{serviceId}', [ServiceController::class, 'delete'])
            ->whereNumber('businessId');

        Route::prefix('{businessId}/services/{serviceId}/bookings')->group(function () {
            // === Rutas de Bookings (1-1 con User) (1-N desde Services) ===
            Route::post('/v2', [BookingController::class, 'create'])
                ->whereNumber(['businessId', 'serviceId']);
            Route::patch('{bookingId}/v2', [BookingController::class, 'updateBookingStatus'])
                ->whereNumber(['businessId', 'serviceId', 'bookingId']);
        });

        // Rutas de PreBookings Privadas
        /*Route::post('{businessId}/services/{serviceId}/bookings', [PreBookingController::class, 'create'])
            ->whereNumber(['businessId', 'serviceId']);*/

        // Cambiar entre postcontroller sin bus y con bus
        Route::post('{businessId}/services/{serviceId}/bookings', PreBookingPostControllerWithBus::class)
            ->whereNumber(['businessId', 'serviceId']);
        Route::delete('{businessId}/services/{serviceId}/bookings/{bookingId}', PreBookingDeleteControllerWithBus::class)
            ->whereNumber(['businessId', 'serviceId', 'bookingId']);
    });
});
