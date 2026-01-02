<?php

namespace App\DTO\Booking;

use App\DTO\User\UserResponseDTO;
use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\PreBooking;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use stdClass;

class BookingResponseDTO implements Arrayable, JsonSerializable
{

    public function __construct(private ?int             $bookingId,
                                private int              $serviceId,
                                private string           $startDate,
                                private string           $endDate,
                                private ?BookingStatus          $status = null,
                                private ?UserResponseDTO $userResponse = null,)
    {

    }

    public static function createFromPreBookingModel(PreBooking $booking): self
    {
        return new self(
            bookingId: $booking->id,
            serviceId: $booking->service_id,
            startDate: $booking->start_date,
            endDate: $booking->end_date,
        );
    }

    public static function createFromBookingModel(Booking $booking, bool $includeUser): self
    {
        return new self(
            bookingId: $booking->id,
            serviceId: $booking->service_id,
            startDate: $booking->start_date,
            endDate: $booking->end_date,
            status: $booking->status,
            userResponse: $includeUser ? UserResponseDTO::createFromModel($booking->user) : null
        );
    }

    public static function createFromStdClass(stdClass $row): self
    {
        return new self(
            $row->id,
            $row->service_id,
            $row->start_date,
            $row->end_date,
            $row->status,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->bookingId,
            'service_id' => $this->serviceId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'status' => $this->status,
            'user' => $this->userResponse, // UserResponseDTO ya implementa JsonSerializable
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
