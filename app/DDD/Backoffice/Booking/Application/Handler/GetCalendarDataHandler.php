<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Query\GetCalendarDataQuery;
use App\DDD\Backoffice\Booking\Domain\Service\CalendarReadService;
use App\DTO\Calendar\CalendarResponse;

final readonly class GetCalendarDataHandler
{
    public function __construct(
        private CalendarReadService $calendarReadService
    )
    {
    }

    public function __invoke(GetCalendarDataQuery $query): CalendarResponse
    {
        return $this->calendarReadService->getCalendarData($query->serviceId);
    }
}
