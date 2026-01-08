<?php

namespace App\DTO\Booking;

use App\Models\PreBooking;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

readonly class BookingRequestDTO implements Arrayable, JsonSerializable
{

    public function __construct(
        public int      $serviceId,
        private string  $startDate,
        private string  $endDate,

        private string  $userName,
        private string  $userEmail,
        private string  $userPass,
        private ?string $userPhone = null,

        private ?int    $bookingId = null,
    )
    {
    }

    public static function createFromArray(array $data, int $serviceId, ?int $bookingId = null): self
    {
        return new self(
            $serviceId,
            $data['start_date'],
            $data['end_date'],
            $data['user_name'],
            $data['user_email'],
            $data['user_pass'],
            $data['user_phone'] ?? null,
            $bookingId,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->bookingId,
            'service_id' => $this->serviceId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,

            'user_name' => $this->userName,
            'user_email' => $this->userEmail,
            'user_phone' => $this->userPhone,
            'user_pass' => $this->userPass,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
