<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Booking;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\CreatePreBookingHandler;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DTO\Booking\BookingRequestDTO;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Throwable;

class PostController
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

    public function __construct(private CreatePreBookingHandler $handler)
    {

    }

    // todo: el dto lleva value objects (en este caso no es necesario un DTO) además aqui les gusta mas hacer payloads que son básicamente lo que necesita el caso de uso para funcionar, y el command si lleva los ids, y el objeto dto dentro para no engorrar todo de datos, esa es la clave que no estaba entendiendo
    // todo: el value object siempre usará el constructor de manera
    // protegida o privada y solo habrán ciertas maneras de crear los
    // objetos, por ejemplo, create as Seconds en un value object time o un createAsMinutes sabes para ser más semanticos y si hay alguna manera de crearlos distintos

    // TODO: Usar un bus?
    // TODO: Realmente no es un command porque esta devolviendo un objeto de Respuesta - Debe ir en Query
    public function __invoke(int $businessId,
                             int $serviceId,
                             Request $request): JsonResponse
    {
        try {
            $this->validateBookings($request);

            $dto = BookingRequestDTO::createFromArray(
                $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
                $serviceId,
                null,
                $request->user()->id, // TODO: Este acceso realmente no lo haría un controller no?
            );

            $command = CreatePreBookingCommand::fromPrimitives(
                $businessId,
                $serviceId,
                $dto->authUserId,
                $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
                $request->user()->isCliente(),
            );

            $response = ($this->handler)($command);

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
