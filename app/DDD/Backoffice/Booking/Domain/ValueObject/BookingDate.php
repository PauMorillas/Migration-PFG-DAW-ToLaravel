<?php

namespace App\DDD\Backoffice\Booking\Domain\ValueObject;

use App\DDD\Backoffice\Booking\Domain\Exception\BookingDateIsOnPastException;
use App\DDD\Backoffice\Shared\ValueObject\Date;

final readonly class BookingDate extends Date
{
    // El formato de las fechas de una reserva tiene formato distinto
    protected const FORMAT = 'Y-m-d H:i:s';
    protected function __construct(string $date, ?bool $checkIfIsOnPast = true)
    {
        parent::__construct($date, $checkIfIsOnPast);

        if ($checkIfIsOnPast && $this->value->isPast()) {
            throw new BookingDateIsOnPastException();
        }
    }
}
