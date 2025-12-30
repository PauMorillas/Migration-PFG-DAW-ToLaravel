<?php

namespace App\Http\Controllers;

use App\DTO\Booking\BookingRequestDTO;
use App\Exceptions\AppException;
use App\Services\BookingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Throwable;

class BookingController extends Controller
{

    use ApiResponseTrait;

    public function __construct(private readonly BookingService $bookingService)
    {

    }

    private const PREBOOKING_ATTRIBUTES = [
        'start_date' => 'fecha de inicio',
        'end_date' => 'fecha de fin',
        'user_name' => 'nombre de usuario',
        'user_email' => 'correo electronico',
        'user_phone' => 'telefono',
        'user_pass' => 'contraseña',
    ];

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

    public function create(int $businessId, int $serviceId, Request $request): JsonResponse
    {
        try {
            $this->validateBookings($request);
            $dto = BookingRequestDTO::createFromArray($request->all(), $serviceId);
            $bookingResp = $this->bookingService->create($businessId, $dto);

            return $this->ok($bookingResp);
        } catch (ValidationException $th) {
            return $this->error($th->validator->errors()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function update(int $businessId, int $serviceId, int $bookingId): JsonResponse
    {
        return $this->noContent();
    }

    private function validateBookings(Request $request): void
    {
        $validator = Validator::make(
            $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
            [
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'user_name' => 'required|string|max:255',
                'user_email' => 'required|email|unique:users,email|unique:pre_bookings,user_email|max:255',
                'user_phone' => 'required|max:9',
                'user_pass' => ['required', 'string', 'max:255',
                    Password::min(8)
                        ->letters()
                        ->numbers()
                ]
            ],
            [
                '*.required' => 'El campo :attribute es obligatorio.',
                '*.string' => 'El campo :attribute debe ser un texto.',
                '*.email' => 'El campo :attribute debe tener un formato valido.',
                '*.unique' => 'El :attribute ya esta registrado.',
                '*.max' => 'La :attribute debe tener al menos :max caracteres.',
                'user_pass.letters' => 'La :attribute debe contener al menos una letra.',
                'user_pass.numbers' => 'La :attribute debe contener al menos un número.',
                'user_pass.min' => 'La :attribute debe tener al menos :min caracteres.',
                'end_date.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            ],
            self::PREBOOKING_ATTRIBUTES
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
