<?php

namespace App\Services;

use App\DTO\Booking\BookingResponseDTO;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;

readonly class BookingService
{
    public function __construct(private BookingRepositoryInterface $bookingRepository,
                                private ServiceService $serviceService)
    {
    }

    public function findAll(int $businessId, int $serviceId, int $userId): array
    {
        // TODO: VALIDACIONES DE USUARIO??
        $this->serviceService->findById($businessId, $serviceId);

        $bookings = $this->bookingRepository->findAll($businessId);

        return $bookings->map(callback: function (Booking $booking) {
            return BookingResponseDTO::createFromBookingModel($booking, $booking->user);
            // Para acceder a una entidad de eloquent (que tenga relaciones definidas)
            // se hace como si fuese una propiedad
        })->toArray();
    }
}
