<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Booking;

use App\DDD\Backoffice\Booking\Application\Command\FindByIdPreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\FindByIdPreBookingHandler;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class GetController
{
    Use ApiResponseTrait;

    public function __construct(private FindByIdPreBookingHandler $handler)
    {

    }

    public function __invoke(int $businessId,
                             int $serviceId,
                             int $bookingId,
                             Request $request): JsonResponse {
        try {
            // TODO: Hace falta un DTO para pasar solo ids?
            $user = $request->user();
            $command = FindByIdPreBookingCommand::fromPrimitives
            ($businessId, $serviceId, $bookingId, $user->id, $user->isCliente());

            $response = ($this->handler)($command);

            return $this->ok($response);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
