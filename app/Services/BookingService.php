<?php

namespace App\Services;

use App\DTO\Booking\BookingDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Exceptions\BookingDoesntBelongToServiceException;
use App\Exceptions\BookingNotFoundException;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;

readonly class BookingService
{
    public function __construct(private BookingRepositoryInterface $bookingRepository,
                                private ServiceService             $serviceService)
    {
    }

    public
    function findById(int $businessId, int $serviceId, int $userId, int $bookingId): BookingResponseDTO
    {
        // TODO: VALIDACIONES DE USUARIO(GERENTE) QUE SEGURAMENTE SE HAGAN EN EL FIND DE business
        $this->assertExists($bookingId);

        $this->serviceService->findById($businessId, $serviceId);

        $booking = $this->getBookingModelOrFail($bookingId);

        $this->assertBookingBelongsToService($booking, $serviceId);

        return BookingResponseDTO::createFromBookingModel($booking, $booking->user);
    }

    public
    function findAllByBusinessId(int $businessId, int $serviceId): array
    {
        // TODO: VALIDACIONES DE USUARIO Gerente??
        $this->serviceService->findById($businessId, $serviceId);

        $bookings = $this->bookingRepository->findAllByBusinessId($businessId);

        return $bookings->map(callback: function (Booking $booking) {
            return BookingResponseDTO::createFromBookingModel($booking, $booking->user);
            // Para acceder a una entidad de eloquent (que tenga relaciones definidas)
            // se hace como si fuese una propiedad
        })->toArray();
    }

    // TODO: VAS POR AKI
    public function updateBookingStatus(BookingDTO $bookingDTO, int $businessId): BookingResponseDTO
    {
        // TODO: VALIDACIONES DE USUARIO (Gerente)
        $this->serviceService->findById($businessId, $bookingDTO->serviceId);
        $booking = $this->getBookingModelWithUserOrFail($bookingDTO->bookingId);
        $booking = $this->bookingRepository->updateBookingStatus($booking, $bookingDTO->toArray());

        return BookingResponseDTO::createFromBookingModel($booking, $booking->user);
    }

    private
    function getBookingModelOrFail(int $bookingId): ?Booking
    {
        $booking = $this->bookingRepository->findById($bookingId);

        if (is_null($booking)) {
            throw new BookingNotFoundException();
        }

        return $booking;
    }

    private function getBookingModelWithUserOrFail(int $bookingId): ?Booking {
        $booking = $this->bookingRepository->findById($bookingId);

        if (is_null($booking)) {
            throw new BookingNotFoundException();
        }

        return $booking;
    }

    private
    function assertExists(int $bookingId): bool
    {
        $exists = $this->bookingRepository->assertExists($bookingId);

        if (is_null($exists) || !$exists) {
            throw new BookingNotFoundException();
        }

        return $exists;
    }

    private
    function assertBookingBelongsToService(Booking $booking, int $serviceId): void
    {
        if ($booking->service_id !== $serviceId) {
            throw new BookingDoesntBelongToServiceException();
        }
    }
}
