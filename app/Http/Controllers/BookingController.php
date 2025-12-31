<?php

namespace App\Http\Controllers;

use App\DTO\Booking\BookingDTO;
use App\DTO\Booking\BookingRequestDTO;
use App\Enums\BookingStatus;
use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
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

    public function updateBookingStatus(int     $businessId, int $serviceId, int $bookingId,
                                        Request $request)
    {
        try {
            $userId = 1;
            $status = BookingStatus::from($request->get('status'));
            $bookingDTO = BookingDTO::createFromArray($request->all(), $serviceId, $userId, $bookingId, $status);

            var_dump($bookingDTO);
            $bookingResp = $this->bookingService->updateBookingStatus($bookingDTO, $businessId, $serviceId, $userId, $bookingId);
            return $this->ok($bookingResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
