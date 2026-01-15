<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Booking;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\CommandBusInterface;
use App\DDD\Backoffice\User\Domain\Service\UserAuthService;
use App\DTO\Booking\BookingRequestDTO;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostControllerWithBus
{

    Use ApiResponseTrait;

    private const PREBOOKING_ATTRIBUTES = [
        'start_date' => 'fecha de inicio',
        'end_date' => 'fecha de fin',
        'user_name' => 'nombre de usuario',
        'user_email' => 'correo electronico',
        'user_phone' => 'telefono',
        'user_pass' => 'contraseña',
    ];

    public function __construct()
    {

    }

    public function __invoke(int $businessId,
                             int $serviceId,
                             Request $request): JsonResponse
    {
        try {
            $authUser = UserAuthService::createFromAuth();

            $this->validateBookings($request);

            $dto = BookingRequestDTO::createFromArray(
                $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
                $serviceId,
                null,
                $authUser->getAuthUserId()->value(),
            );

            $command = CreatePreBookingCommand::fromPrimitives(
                $businessId,
                $serviceId,
                $dto->authUserId,
                $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
                $authUser->isCliente(),
            );

            $bus = app(CommandBusInterface::class);
            $response = $bus->dispatch($command);

            return $this->created($response);
        } catch (ValidationException $th) {
            return $this->error($th->validator->getMessageBag()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
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
