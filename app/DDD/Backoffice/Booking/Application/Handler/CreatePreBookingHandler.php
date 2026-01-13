<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Booking\Domain\Service\PreBookingServiceV2;
use App\DTO\Booking\BookingRequestDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Services\PreBookingService;

readonly class CreatePreBookingHandler
{
    public function __construct(
        private PreBookingServiceV2 $preBookingService
    ){}

    public function __invoke(CreatePreBookingCommand $command): BookingResponseDTO {
        return $this->preBookingService->create(
            businessId: $command->businessId,
            bookingRequestDTO: BookingRequestDTO::createFromArrayCommand(
                $command->toPrimitives()),
            authUserId: $command->authUserId,
            includeUser: $command->includeUser,
        );
    }
}
