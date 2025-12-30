<?php

namespace App\DTO\Booking;

use App\Models\PreBooking;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

readonly class BookingRequestDTO implements Arrayable, JsonSerializable
{

    public function __construct(private ?int    $bookingId,
                                public int     $serviceId,
                                private string  $startDate,
                                private string  $endDate,

                                private string  $userName,
                                private string  $userEmail,
                                private ?string $userPhone,
                                private string  $userPass
    )
    {
    }

    public static function createFromArray(array $data, int $serviceId, ?int $bookingId = null): self
    {
        return new self(
            $bookingId,
            $serviceId,
            $data['start_date'],
            $data['end_date'],
            $data['user_name'],
            $data['user_email'],
            $data['user_phone'],
            $data['user_pass'],
        );
    }

    public static function createFromModel(PreBooking $booking): self
    {
        return new self(
            $booking->id,
            $booking->service_id,
            $booking->start_date,
            $booking->end_date,
            $booking->user_name,
            $booking->user_email,
            $booking->user_phone,
            $booking->user_pass
        );
    }

    public function toArray(): array
    {
        return [
            'booking_id' => $this->bookingId, // TODO: Se llamará asi o simplemente ID?
            'service_id' => $this->serviceId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,

            'user_name' => $this->userName,
            'user_email' => $this->userEmail,
            'user_phone' => $this->userPhone,
            'user_pass' => $this->userPass,
        ];
    }

    public
    function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
