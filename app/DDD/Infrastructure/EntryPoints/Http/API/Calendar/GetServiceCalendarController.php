<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Calendar;

use App\DDD\Backoffice\Booking\Application\Handler\GetServiceCalendarDataHandler;
use App\DDD\Backoffice\Booking\Application\Query\GetServiceCalendarDataQuery;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class GetServiceCalendarController
{
    use ApiResponseTrait;

    public function __construct(
        private readonly GetServiceCalendarDataHandler $handler
    ) {}

    public function __invoke(int $serviceId): JsonResponse
    {
        try {
            $query = GetServiceCalendarDataQuery::createFromInt($serviceId);

            $response = ($this->handler)($query);

            return $this->ok($response->toArray());

        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
