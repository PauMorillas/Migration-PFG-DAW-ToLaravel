<?php

namespace App\DDD\Backoffice\Booking\Application\Query;

use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;

class GetCalendarDataQuery
{
    private function __construct(
        public ServiceId $serviceId
    ) {}

    public static function createFromInt(int $serviceId): self
    {
        return new self(
            ServiceId::createFromInt($serviceId)
        );
    }
}
