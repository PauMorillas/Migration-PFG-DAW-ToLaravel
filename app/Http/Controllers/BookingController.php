<?php

namespace App\Http\Controllers;

use App\DTO\Booking\BookingDTO;
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

    // TODO: Hacer un método para OBTENER RESERVAS QUE SE SOLAPEN
    public function findAll(int $businessId, int $serviceId)
    {
        try {
            $bookingResp = $this->bookingService->findAllByBusinessId($businessId, $serviceId);
            return $this->ok($bookingResp);
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
            $status = BookingStatus::from($request->get('status'));
            $bookingDTO = BookingDTO::createFromArray($request->all(),
                $serviceId, $bookingId, $status);

            $bookingResp = $this->bookingService->updateBookingStatus($bookingDTO, $businessId);
            return $this->ok($bookingResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
