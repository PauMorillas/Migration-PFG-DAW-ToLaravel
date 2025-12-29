<?php

namespace App\Http\Controllers;

use App\DTO\Booking\BookingRequestDTO;
use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingController extends Controller
{

    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    public function findById(int $businessId, int $serviceId, int $bookingId): JsonResponse
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

    // TODO: ESTE MÉTODO ES EL QUE USA stdClass en la función del repo (Aun no separé el queryBuilder)
    // Obtiene todas las reservas de un determinado negocio
    // todo: Posteriormente habrá que hacer el resto de findAll's(Por servicio, que estén activas...)
    public function findAll(int $businessId, int $serviceId): JsonResponse
    {
        try {
            $bookingsResp = $this->bookingService->findAll($businessId, $serviceId);

            return $this->ok($bookingsResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function delete(int $businessId, int $serviceId, int $bookingId): JsonResponse
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

    public function create(): JsonResponse
    {
        // $dto = BookingRequestDTO::createFromArray($request->all(), $serviceId, $bookingId);
        return $this->noContent();
    }

    public function update(int $businessId, int $serviceId, int $bookingId): JsonResponse
    {
        return $this->noContent();
    }
}
