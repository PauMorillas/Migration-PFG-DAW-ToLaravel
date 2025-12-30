<?php

namespace App\Http\Controllers;

use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class BookingController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    // TODO: OBTENER TODAS LAS RESERVAS, OBTENER RESERVAS QUE SE SOLAPEN

    public function findAll(int $businessId, int $serviceId)
    {
        // TODO: Sacar el id del cliente de la sesión de sanctum
        $userId = -1;
        // TODO: Objeto de respuesta
        $resp = $this->bookingService->findAll($businessId, $serviceId, $userId);
        return $this->ok($resp);
    }

    public function updateBookingStatus(Request $request)
    {
        return $this->noContent();
    }
}
