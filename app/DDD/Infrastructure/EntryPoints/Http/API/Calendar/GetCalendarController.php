<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Calendar;

use App\DDD\Backoffice\Booking\Application\Handler\GetCalendarDataHandler;
use App\DDD\Backoffice\Booking\Application\Query\GetCalendarDataQuery;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class GetCalendarController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly GetCalendarDataHandler $handler
    ) {}

    public function __invoke(int $serviceId): JsonResponse
    {
        try {
            $query = GetCalendarDataQuery::createFromInt($serviceId);

            $response = ($this->handler)($query);

            return $this->ok($responseDto->toArray());

        } catch (AppException $th) {
            // Excepciones controladas de dominio
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            // Excepciones no controladas
            return $this->internalError($th);
        }
    }
}
