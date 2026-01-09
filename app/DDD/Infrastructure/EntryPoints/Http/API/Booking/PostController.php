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

    // todo: el dto lleva value objects (en este caso no es necesario) además aqui les gusta mas hacer payloads que son básicamente lo que necesita el caso de uso para funcionar, y el command si lleva los ids, y el objeto dto dentro para no engorrar todo de datos, esa es la clave que no estaba entendiendo
    // todo: el value object siempre usará el constructor de manera
    // protegida o privada y solo habrán ciertas maneras de crear los
    // objetos, por ejemplo, create as Seconds en un value object time o un createAsMinutes sabes para ser más semanticos y si hay alguna manera de crearlos distintos

    // TODO: Usar un bus?
    // TODO: hacer el objeto (que extienda de Object) en vez de un modelo Eloquent
    // TODO: Realmente no es un command porque esta devolviendo un objeto de Respuesta - Debe ir en Query
    public function __invoke(int $businessId,
                             int $serviceId,
                             Request $request): JsonResponse
    {
        try {
            // TODO: Al command se le pasa el DTO? para que mapee los primitivos?
            $dto = BookingRequestDTO::createFromArray(
                $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
                $serviceId,
                $request->user()->id,
            );

            $command = CreatePreBookingCommand::fromPrimitives(
                $businessId,
                $serviceId,
                $dto->authUserId,
                $request->only(array_keys(self::PREBOOKING_ATTRIBUTES)),
            );

            $response = ($this->handler)($command);

            return $this->created($response);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }

        // todo: usar el create de la entidad en vez de el constructor
        /*$command = new CreatePreBookingCommand(
            businessId: new BusinessId($businessId),
            serviceId: $serviceId,
            authUserId: $request->user()->id, // TODO: Este acceso realmente no lo haría un controller no?
                                              // Lo haría un repo o un service? Porque esta tocando modelos
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            userName: $request->input('user_name'),
            userEmail: $request->input('user_email'),
            userPhone: $request->input('user_phone'),
            userPass: $request->input('user_pass'),
        );*/


    }
}
