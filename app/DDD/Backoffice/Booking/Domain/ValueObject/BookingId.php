<?php

namespace App\DDD\Backoffice\Booking\Domain\ValueObject;

use App\DDD\Backoffice\Shared\ValueObject\Id;

final readonly class BookingId extends Id
{
    protected function __construct(int $value)
    {
        parent::__construct($value);
    }
}
