<?php

namespace App\DDD\Backoffice\Booking\Domain\ValueObject;

use App\DDD\Backoffice\Booking\Domain\Exception\BookingDateIsOnPastException;
use App\DDD\Backoffice\Shared\ValueObject\Date;
use Carbon\Carbon;
use DomainException;

final readonly class BookingDate extends Date
{
    protected const FORMAT = 'Y-m-d H:i:s';
    public function __construct(string $date)
    {
        parent::__construct($date);

        if ($this->value->isPast()) {
            throw new BookingDateIsOnPastException();
        }
    }
}
