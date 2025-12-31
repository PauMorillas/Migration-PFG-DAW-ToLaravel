<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;
use Throwable;

class BookingController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    // TODO: OBTENER TODAS LAS RESERVAS, OBTENER RESERVAS QUE SE SOLAPEN
    public function findAll(int $businessId, int $serviceId)
    {
        try {
            // TODO: Sacar el id del cliente de la sesión de sanctum
            $userId = -1;
            // TODO: Objeto de respuesta
            $resp = $this->bookingService->findAll($businessId, $serviceId, $userId);
            return $this->ok($resp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function updateBookingStatus(Request $request)
    {
        return $this->noContent();
    }
}
