<?php

namespace App\DDD\Backoffice\Booking\Application\Command;

use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;

final readonly class DeletePreBookingCommand
{
    protected function __construct(
        private BookingId $bookingId,
        private BusinessId $businessId,
        private ServiceId $serviceId,
    )
    {
    }

    public static function createFromPrimitives(int $bookingId, int $businessId, int $serviceId): self {
        return new self(
            BookingId::createFromInt($bookingId),
            BusinessId::createFromInt($businessId),
            ServiceId::createFromInt($serviceId)
        );
    }

    public function getBookingId(): BookingId {
        return $this->bookingId;
    }

    public function getBusinessId(): BusinessId {
        return $this->businessId;
    }

    public function getServiceId(): ServiceId {
        return $this->serviceId;
    }
}
