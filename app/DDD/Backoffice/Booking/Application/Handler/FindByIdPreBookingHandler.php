<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Command\FindByIdPreBookingCommand;
use App\DDD\Backoffice\Booking\Domain\Service\PreBookingServiceV2;
use App\DTO\Booking\BookingResponseDTO;

readonly class FindByIdPreBookingHandler
{
    public function __construct(
        private PreBookingServiceV2 $preBookingService
    ) {}

    public function __invoke(FindByIdPreBookingCommand $command): BookingResponseDTO
    {
        return $this->preBookingService->findById(
            $command->businessId,
            $command->serviceId,
            $command->bookingId,
            $command->authUserId,
            $command->includeUser
        );
    }
}
