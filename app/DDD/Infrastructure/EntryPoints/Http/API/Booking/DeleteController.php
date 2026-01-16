<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Booking;

use App\DDD\Backoffice\Booking\Application\Command\DeletePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\DeletePreBookingHandler;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\CommandBusInterface;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class DeleteController
{
    Use ApiResponseTrait;

    public function __construct(DeletePreBookingHandler $handler)
    {
    }

    public function __invoke(int $businessId,  int $serviceId, int $bookingId): JsonResponse
    {
        try {
            $command = DeletePreBookingCommand::createFromPrimitives($bookingId, $businessId, $serviceId);

            $bus = app(CommandBusInterface::class);
            // no hay respuesta al ser un delete
            $bus->dispatch($command);

            return $this->noContent();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
