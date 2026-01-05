<?php

namespace App\Http\Controllers;

use App\DTO\Booking\BookingDTO;
use App\Enums\BookingStatus;
use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    private const BOOKING_ATTRIBUTES = [
        'start_date' => 'fecha de inicio',
        'end_date' => 'fecha de fin',
        'status' => 'estado'
    ];

    private const BOOKING_UPDATE_ATTRIBUTES = [
        'status' => 'estado'
    ];

    /* TODO: Hacer un método para OBTENER RESERVAS QUE SE SOLAPEN
    *       Y el create también
    */
    public function findAll(int $businessId, int $serviceId, Request $request): JsonResponse
    {
        try {
            $user = $request->user(); // null si no hay token
            $isGerente = $user?->isGerente() ?? false;
            var_dump($isGerente);
            $bookingResp = $this->bookingService->findAllByBusinessId($businessId, $serviceId, $isGerente);

            return $this->ok($bookingResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function findById(int $businessId, int $serviceId, int $bookingId, Request $request): JsonResponse
    {
        try {
            $user = $request->user(); // null si no hay token
            $isGerente = $user?->isGerente() ?? false;
            $bookingResp = $this->bookingService->findById($businessId, $serviceId, $bookingId, $isGerente);

            return $this->ok($bookingResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    // Me da que no se consumirá mediante endpoint,
    // -> la consumirá el service de PreBooking al confirmar la PreReserva
    public function create(int $businessId, int $serviceId, Request $request): JsonResponse
    {
        try {
            $this->validateBookings($request, [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'status' => ['required', new Enum(BookingStatus::class)],
            ]);
            $userId = $request->user()->id;
            // El status por defecto en este punto será activa
            $bookingDTO = BookingDTO::createFromArray($request->all(), $serviceId,
                BookingStatus::ACTIVA, null, $userId);
            $bookingResp = $this->bookingService->create($businessId, $bookingDTO);

            return $this->ok($bookingResp);
        } catch (ValidationException $th) {
            return $this->error($th->validator->errors()->first());
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
            $this->validateBookings($request, [
                'status' => ['required', new Enum(BookingStatus::class)],
            ], self::BOOKING_UPDATE_ATTRIBUTES);
            $status = BookingStatus::from($request->get('status'));

            $isGerente = $request->user()->isGerente();
            $userId = $request->user()->id;

            $bookingDTO = BookingDTO::createFromArray($request->all(),
                $serviceId, $status, $bookingId, $userId);

            $bookingResp = $this->bookingService->updateBookingStatus($bookingDTO, $businessId, $isGerente);

            return $this->ok($bookingResp);
        } catch (ValidationException $th) {
            return $this->error($th->validator->errors()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    private function validateBookings(Request $request, array $rules,
                                      ?array  $attributes = self::BOOKING_ATTRIBUTES): void
    {
        $validator = Validator::make(
            $request->only(array_keys($attributes)),
            $rules,
            [
                '*.required' => 'El campo :attribute es obligatorio.',
                'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
                'status.enum' => 'El estado seleccionado no es válido.'
            ],
            $attributes
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
