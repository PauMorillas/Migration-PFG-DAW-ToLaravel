<?php

namespace App\DDD\Backoffice\Booking\Application\Query;

use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;

class GetBusinessCalendarDataQuery
{
    private function __construct(
        public BusinessId $businessId
    ) {}

    public static function createFromInt(int $businessId): self
    {
        return new self(
            BusinessId::createFromInt($businessId)
        );
    }
}
