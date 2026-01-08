<?php

namespace App\DDD\Backoffice\Booking\Application\Command;

readonly class CreatePreBookingCommand
{
    // TODO: He afirmado que un comando recibirá primitivos, no valueObjects
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
