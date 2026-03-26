<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Query\GetBusinessCalendarDataQuery;
use App\DTO\Calendar\CalendarResponse;
use App\Services\CalendarService;

readonly class GetBusinessCalendarDataHandler
{
    public function __construct(
        private CalendarService $calendarService
    ) {}

    public function __invoke(GetBusinessCalendarDataQuery $query): CalendarResponse
    {
        return $this->calendarService->getBusinessCalendarData($query->businessId);
    }
}
