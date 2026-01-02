<?php

namespace App\DTO\Booking;

use App\Enums\BookingStatus;
use App\Models\Booking;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

readonly class BookingDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        public int              $bookingId,
        public int           $serviceId,
        protected string        $startDate,
        protected string        $endDate,
        protected BookingStatus $status)
    {
    }

    public static function createFromArray(array $data, int $serviceId, int $bookingId, BookingStatus $status): self
    {
        return new self(
            $bookingId,
            $serviceId,
            $data['start_date'],
            $data['end_date'],
            $status
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->bookingId,
            'service_id' => $this->serviceId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => $this->status->value
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

}
