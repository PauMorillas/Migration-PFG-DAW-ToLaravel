<?php

namespace App\Services;

use App\DTO\Booking\BookingRequestDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Exceptions\BookingNotFoundException;
use App\Exceptions\PreBookingExpiredException;
use App\Models\PreBooking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use App\Exceptions\BookingDoesntBelongToServiceException;
use Carbon\Carbon;
use Nette\Utils\Random;
use Random\RandomException;
use stdClass;

readonly class PreBookingService
{
    public function __construct(private PreBookingRepositoryInterface $preBookingRepository,
                                private BookingRepositoryInterface    $bookingRepository,
                                private ServiceService                $serviceService,
                                private BusinessService               $businessService)
    {
    }

    private const BOOKING_EXPIRATION_MINS = 30;

    private const NECESSARY_BOOKING_ATTRIBUTES = [
        'start_date' => 'fecha de inicio',
        'end_date' => 'fecha de fin',
    ];

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

        $preBookings = $this->preBookingRepository->findAll($businessId);

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

        $preBooking = $this->preBookingRepository->create($payload);

        return BookingResponseDTO::createFromPreBookingModel($preBooking);
    }

    public function delete(int $businessId, int $serviceId, int $bookingId, int $authUserId): bool
    {
        $this->serviceService->findById($businessId, $serviceId);
        $this->businessService->assertUserCanModifyBusiness($businessId, $authUserId);

        $preBooking = $this->getPreBookingModelOrFail($bookingId);

        $this->assertPreBookingBelongsToService($preBooking, $serviceId);

        $this->preBookingRepository->delete($preBooking);

        return true;
    }

    // TODO: Necesitamos recuperar el id del usuario si no viene de la request (seguramente no por la implementacion de JS)
    // Posible solución-> buscar el usuario con el correo de la prebooking y coger ese id para hacer la relacion en bd
    public function confirmPreBooking(int $serviceId, int $userId, string $token): BookingResponseDTO
    {
        $preBooking = $this->preBookingRepository->findByToken($token);

        if (is_null($preBooking)) {
            throw new BookingNotFoundException();
        }
        // 2026-01-07 08:39:39
        $expirationDate = Carbon::createFromFormat('Y-m-d H:i:s', $preBooking->expiration_date);
        // Si el tiempo de reserva ha expirado se manda una excepción
        if ($expirationDate->isPast()) {
            $this->deleteByPreBookingModel($preBooking);
            throw new PreBookingExpiredException();
        }

        // Una vez validado se guarda una booking
        $bookingData = $preBooking->only(array_keys(self::NECESSARY_BOOKING_ATTRIBUTES));
        $bookingData += [
            'service_id' => $serviceId,
            'user_id' => $userId,
        ];

        $this->bookingRepository->create($bookingData);

        return BookingResponseDTO::createFromPreBookingModel($preBooking);
    }

    private function getPreBookingModelOrFail(int $bookingId): PreBooking
    {
        $preBooking = $this->preBookingRepository->findById($bookingId);

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
            return $this->generateRandomToken();
        }
        /*var_dump(bin2hex($bytes));*/
        return bin2hex($bytes);
    }

    private function deleteByPreBookingModel(PreBooking $preBooking): void {
        $this->preBookingRepository->delete($preBooking);
    }

}
