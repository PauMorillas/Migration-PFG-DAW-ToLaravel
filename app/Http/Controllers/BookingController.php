<?php

namespace App\Http\Controllers;

use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{

    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    public function create(int $id, Request $request)
    {

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

    /*catch (ValidationException $ex) {
    return $this->validationError($ex->validator->errors()->first());
    }*/

    public function findAll(int $idReserva)
    {

    }
}
