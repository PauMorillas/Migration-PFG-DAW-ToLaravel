<?php

namespace App\Http\Controllers;

use App\DTO\Booking\BookingRequestDTO;
use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingController extends Controller
{

    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    public function findById(int $businessId, int $serviceId, int $bookingId)
    {
        try {
            $bookingResp = $this->bookingService->findbyId($businessId, $serviceId, $bookingId);

            return $this->ok($bookingResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function delete(int $businessId, int $serviceId, int $bookingId)
    {
        try {
            $this->bookingService->delete($businessId, $serviceId, $bookingId);

            return $this->noContent();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function findAll(int $idReserva)
    {

    }

    public function create() {
        // $dto = BookingRequestDTO::createFromArray($request->all(), $serviceId, $bookingId);
    }

    public function update(int $businessId, int $serviceId, int $bookingId) {

    }
}
