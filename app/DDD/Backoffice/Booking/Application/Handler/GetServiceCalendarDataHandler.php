<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Query\GetServiceCalendarDataQuery;
use App\DTO\Calendar\CalendarResponse;
use App\Services\CalendarService;

final readonly class GetServiceCalendarDataHandler
{
    public function __construct(
        private CalendarService $calendarService
    )
    {
    }

    public function __invoke(GetServiceCalendarDataQuery $query): CalendarResponse
    {
        return $this->calendarService->getServiceCalendarData($query->serviceId);
    }
}
