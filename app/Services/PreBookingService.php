<?php

namespace App\Services;

use App\DTO\Booking\BookingRequestDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Exceptions\BookingNotFoundException;
use App\Models\PreBooking;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use App\Exceptions\BookingDoesntBelongToServiceException;
use Nette\Utils\Random;
use Random\RandomException;
use stdClass;

readonly class PreBookingService
{
    public function __construct(private PreBookingRepositoryInterface $bookingRepository,
                                private ServiceService                $serviceService,
                                private BusinessService               $businessService,)
    {
    }
    private const BOOKING_EXPIRATION_MINS = 30;

    public function findById(int $businessId, int $serviceId, int $bookingId): ?BookingResponseDTO
    {
        // Esta función valida que el negocio existe y
        // que el service pertencece al business por eso la usaré
        $this->serviceService->findById($businessId, $serviceId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        $this->assertBookingBelongsToService($preBooking, $serviceId);

        return BookingResponseDTO::createFromModel($preBooking);
    }

    public function findAll(int $businessId, int $serviceId): array
    {
        $this->businessService->assertExists($businessId);
        $this->serviceService->assertExists($serviceId);

        $bookings = $this->bookingRepository->findAll($businessId);

        return $bookings->map(function (stdClass $preBooking) {
            return BookingResponseDTO::createFromStdClass($preBooking);
        })->toArray();
    }

    public function create(int $businessId, BookingRequestDTO $data): BookingResponseDTO
    {
        $this->serviceService->findById($businessId, $data->serviceId);

        $payload = $data->toArray() + [
                'token' => $this->generateRandomToken(),
                'expiration_date' => now()->addMinutes(self::BOOKING_EXPIRATION_MINS),
            ];

        $booking = $this->bookingRepository->create($payload);

        return BookingResponseDTO::createFromModel($booking);
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

    private function generateRandomToken(): string
    {
        try {
            $bytes = random_bytes(20);
        } catch (RandomException) {
            $this->generateRandomToken();
        }
        /*var_dump(bin2hex($bytes));*/
        return bin2hex($bytes);
    }

    /* private function validateExpiredPrebooking(): boolean {
        if($preBooking->expiration_date->isPast()) {
            throw new PreBookingExpiredException();
        }
    } */

}
