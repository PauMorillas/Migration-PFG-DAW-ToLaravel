<?php

namespace App\Services;

use App\DTO\Booking\BookingResponseDTO;
use App\Exceptions\BookingNotFoundException;
use App\Models\PreBooking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Exceptions\BookingDoesntBelongToServiceException;

readonly class BookingService
{
    public function __construct(private BookingRepositoryInterface $bookingRepository,
                                private ServiceService             $serviceService,
                                private BusinessService            $businessService,)
    {
    }

    public function findById(int $businessId, int $serviceId, int $bookingId): ?BookingResponseDTO
    {
        // Esta función valida que el negocio existe y
        // que el service pertencece al business por eso la usaré
        $this->serviceService->findById($businessId, $serviceId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        $this->assertBookingBelongsToService($preBooking, $serviceId);

        return BookingResponseDTO::createFromModel($preBooking);
    }

    public function delete(int $businessId, int $serviceId, int $bookingId): bool
    {
        $this->serviceService->findById($businessId, $serviceId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        $this->assertBookingBelongsToService($preBooking, $serviceId);

        $this->bookingRepository->delete($preBooking);

        return true;
    }

    private function getPreBookingModelOrFail(int $bookingId): PreBooking
    {
        $preBooking = $this->bookingRepository->findById($bookingId);

        if (is_null($preBooking)) {
            throw new BookingNotFoundException();
        }

        return $preBooking;
    }

    private function assertBookingBelongsToService(PreBooking $preBooking, int $serviceId): void
    {
        if ($preBooking->service_id !== $serviceId) {
            throw new BookingDoesntBelongToServiceException();
        }
    }

}
