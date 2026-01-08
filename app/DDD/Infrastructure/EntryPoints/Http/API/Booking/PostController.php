<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Booking;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\CreatePreBookingHandler;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Uri;

class PostController
{

    Use ApiResponseTrait;

    public function __construct(private CreatePreBookingHandler $handler)
    {

    }

    // TODO: Usar un bus?
    // TODO: hacer el objeto (que extienda de Object) en vez de un modelo Eloquent
    // TODO: Realmente no es un command porque esta devolviendo un objeto de Respuesta - Debe ir en Query
    public function __invoke(int $businessId,
                             int $serviceId,
                             Request $request): JsonResponse
    {
        // todo: usar el create de la entidad en vez de el constructor
        $command = new CreatePreBookingCommand(
            businessId: $businessId,
            serviceId: $serviceId,
            authUserId: $request->user()->id, // TODO: Este acceso realmente no lo haría un controller no?
                                              // Lo haría un repo o un service? Porque esta tocando modelos
            startDate: $request->input('start_date'),
            endDate: $request->input('end_date'),
            userName: $request->input('user_name'),
            userEmail: $request->input('user_email'),
            userPhone: $request->input('user_phone'),
            userPass: $request->input('user_pass'),
        );

        $result = ($this->handler)($command);

        return $this->created($result);
    }
}
