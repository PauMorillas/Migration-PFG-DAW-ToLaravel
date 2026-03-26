<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\API\Calendar;

use App\DDD\Backoffice\Booking\Application\Handler\GetBusinessCalendarDataHandler;
use App\DDD\Backoffice\Booking\Application\Query\GetBusinessCalendarDataQuery;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Throwable;

class GetBusinessCalendarController
{
    use ApiResponseTrait;

    public function __construct(
        private GetBusinessCalendarDataHandler $handler)
    {

    }

    public function __invoke(int $businessId): JsonResponse
    {
        try {
            $query = GetBusinessCalendarDataQuery::createFromInt($businessId);

            $response = ($this->handler)($query);

            return $this->ok($response->toArray());

        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
