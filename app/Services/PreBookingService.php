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
                                private BusinessService               $businessService)
    {
    }
    private const BOOKING_EXPIRATION_MINS = 30;

    public function findById(int $businessId, int $serviceId, int $bookingId, bool $includeUser): ?BookingResponseDTO
    {
        // Esta función valida que el negocio existe y
        // que el service pertencece al business por eso la usaré
        $this->serviceService->findById($businessId, $serviceId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        $this->assertPreBookingBelongsToService($preBooking, $serviceId);

        return BookingResponseDTO::createFromPreBookingModel($preBooking, $includeUser);
    }

    public function findAll(int $businessId, int $serviceId, bool $includeUser): array
    {
        $this->businessService->assertExists($businessId);
        $this->serviceService->assertExists($serviceId);

        $preBookings = $this->bookingRepository->findAll($businessId);

        return $preBookings->map(function (stdClass $preBooking) use ($includeUser) {
            return BookingResponseDTO::createFromStdClass($preBooking, $includeUser);
        })->toArray();
    }

    public function create(int $businessId, BookingRequestDTO $data, int $authUserId): BookingResponseDTO
    {
        $this->serviceService->findById($businessId, $data->serviceId);
        $this->businessService->assertUserCanModifyBusiness($businessId, $authUserId);

        $payload = $data->toArray() + [
                'token' => $this->generateRandomToken(),
                'expiration_date' => now()->addMinutes(self::BOOKING_EXPIRATION_MINS),
            ];

        $preBooking = $this->bookingRepository->create($payload);

        return BookingResponseDTO::createFromPreBookingModel($preBooking);
    }

    public function delete(int $businessId, int $serviceId, int $bookingId, int $authUserId): bool
    {
        $this->serviceService->findById($businessId, $serviceId);
        $this->businessService->assertUserCanModifyBusiness($businessId, $authUserId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        $this->assertPreBookingBelongsToService($preBooking, $serviceId);

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

    private function assertPreBookingBelongsToService(PreBooking $preBooking, int $serviceId): void
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

    // TODO: Validar que el tiempo para aceptar la PreRseserva no expiró
    /* private function validateExpiredPrebooking(): boolean {
        if($preBooking->expiration_date->isPast()) {
            throw new PreBookingExpiredException();
        }
    } */

}
