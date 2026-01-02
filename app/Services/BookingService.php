<?php

namespace App\Services;

use App\DTO\Booking\BookingDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Exceptions\BookingDoesntBelongToServiceException;
use App\Exceptions\BookingNotFoundException;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;

readonly class BookingService
{
    public function __construct(private BookingRepositoryInterface $bookingRepository,
                                private ServiceService             $serviceService)
    {
    }

    public function findById(int $businessId, int $serviceId,
                             int $userId, int $bookingId, bool $includeUser): BookingResponseDTO
    {
        // TODO: VALIDACIONES DE USUARIO(GERENTE) QUE SEGURAMENTE SE HAGAN EN EL FIND DE business
        $this->assertExists($bookingId);

        $this->serviceService->findById($businessId, $serviceId);

        $booking = $includeUser
            ? $this->getBookingModelWithUserOrFail($bookingId)
            : $this->getBookingModelOrFail($bookingId);

        $this->assertBookingBelongsToService($booking, $serviceId);

        return BookingResponseDTO::createFromBookingModel($booking, $includeUser);
    }

    public function findAllByBusinessId(int $businessId, int $serviceId): array
    {
        // TODO: VALIDACIONES DE USUARIO Gerente??
        $this->serviceService->findById($businessId, $serviceId);

        $bookings = $this->bookingRepository->findAllByBusinessId($businessId);

        return $bookings->map(callback: function (Booking $booking) {
            $includeUser = true;
            return BookingResponseDTO::createFromBookingModel($booking, $includeUser);
            // Para acceder a una entidad de eloquent (que tenga relaciones definidas)
            // se hace como si fuese una propiedad
        })->toArray();
    }

    // todo: sacar el idCliente de la sesión
    public function create(int $businessId, BookingDTO $bookingDTO): BookingResponseDTO
    {
        $this->serviceService->findById($businessId, $bookingDTO->serviceId);

        $booking = $this->bookingRepository->create($bookingDTO->toArray());
        $includeUser = true;
        return BookingResponseDTO::createFromBookingModel($booking, $includeUser);
    }

    public function updateBookingStatus
    (BookingDTO $bookingDTO, int $businessId): BookingResponseDTO
    {
        $includeUser = true;
        // TODO: VALIDACIONES DE USUARIO (Gerente)
        $this->serviceService->findById($businessId, $bookingDTO->serviceId);
        $booking = $this->getBookingModelWithUserOrFail($bookingDTO->bookingId);

        $data = $bookingDTO->toArray();
        $updateData = ['status' => $data['status']];
        // Quitamos el user id y resto de datos por posible actualización de estos
        // (Puede resolverse con un DTO para este caso de uso)
        $booking = $this->bookingRepository->updateBookingStatus($booking, $updateData);

        return BookingResponseDTO::createFromBookingModel($booking, $includeUser);
    }

    private function getBookingModelOrFail(int $bookingId): ?Booking
    {
        $booking = $this->bookingRepository->findById($bookingId);

        if (is_null($booking)) {
            throw new BookingNotFoundException();
        }

        return $booking;
    }

    private function getBookingModelWithUserOrFail(int $bookingId): ?Booking
    {
        $booking = $this->bookingRepository->findByIdWithUser($bookingId);

        if (is_null($booking)) {
            throw new BookingNotFoundException();
        }

        return $booking;
    }

    private function assertExists(int $bookingId): bool
    {
        $exists = $this->bookingRepository->assertExists($bookingId);

        if (is_null($exists) || !$exists) {
            throw new BookingNotFoundException();
        }

        return $exists;
    }

    private function assertBookingBelongsToService(Booking $booking,
                                                   int     $serviceId): void
    {
        if ($booking->service_id !== $serviceId) {
            throw new BookingDoesntBelongToServiceException();
        }
    }
}
