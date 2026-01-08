<?php

namespace App\DDD\Backoffice\Booking\Domain\Entity;

class PreBooking
{
    public function __construct(
        public int $businessId,
        public int $serviceId,
        public int $authUserId,
        public string $startDate,
        public string $endDate,
        public string $userName,
        public string $userEmail,
        public string $userPhone,
        public string $userPass,
    )
    {

    }
}
