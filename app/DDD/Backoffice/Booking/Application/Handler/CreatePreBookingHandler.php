<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DTO\Booking\BookingRequestDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Services\PreBookingService;

readonly class CreatePreBookingHandler
{
    public function __construct(
        private PreBookingService $preBookingService
    ){}

    public function __invoke(CreatePreBookingCommand $command): BookingResponseDTO {
        return $this->preBookingService->create(
            businessId: $command->businessId,
            data: new BookingRequestDTO(
                serviceId: $command->serviceId,
                startDate: $command->startDate,
                endDate: $command->endDate,
                userName: $command->userName,
                userEmail: $command->userEmail,
                userPass: $command->userPass,
                userPhone: $command->userPhone
            ),
            authUserId: $command->authUserId
        );
    }
}
