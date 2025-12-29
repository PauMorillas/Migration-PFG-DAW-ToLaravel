<?php

namespace App\Services;

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

    public function delete(int $businessId, int $serviceId, int $bookingId): bool
    {

        // Esta funcion valida que el negocio existe y
        // que el service pertencece al business por eso la uso aquí
        $this->serviceService->findById($businessId, $serviceId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        if ($preBooking->service_id !== $serviceId) {
            throw new BookingDoesntBelongToServiceException();
        }

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


}
