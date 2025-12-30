<?php

namespace App\DTO\Booking;

use App\Models\PreBooking;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use stdClass;

class BookingResponseDTO implements Arrayable, JsonSerializable
{

    public function __construct(private ?int   $bookingId,
                                private int    $serviceId,
                                private string $startDate,
                                private string $endDate)
    {

    }

    public static function createFromModel(PreBooking $booking) {
        return new self(
            bookingId: $booking->id,
            serviceId: $booking->service_id,
            startDate: $booking->start_date,
            endDate: $booking->end_date,
        );
    }

    public static function createFromStdClass(stdClass $row): self
    {
        return new self(
            $row->id,
            $row->service_id,
            $row->start_date,
            $row->end_date,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->bookingId,
            'service_id' => $this->serviceId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
