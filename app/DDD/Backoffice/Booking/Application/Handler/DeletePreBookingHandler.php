<?php

namespace App\DDD\Backoffice\Booking\Application\Handler;

use App\DDD\Backoffice\Booking\Application\Command\DeletePreBookingCommand;
use App\DDD\Backoffice\Booking\Domain\Service\PreBookingServiceV2;
use App\DTO\Booking\BookingResponseDTO;

final readonly class DeletePreBookingHandler
{
    public function __construct(
        private PreBookingServiceV2 $preBookingService,
    )
    {
    }

    /*public function __invoke(DeletePreBookingCommand $command): BookingResponseDTO {
        return $this->preBookingService->delete(
            $command->getBookingId(),
            $command->getBusinessId(),
            $command->getServiceId(),
        );
    }*/
}
