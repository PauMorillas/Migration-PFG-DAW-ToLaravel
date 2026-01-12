<?php

namespace App\DDD\Backoffice\Booking\Domain\Service;

use App\DDD\Backoffice\Booking\Domain\Entity\PreBooking;
use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Booking\Infrastructure\Persistence\EloquentPreBookingRepository;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\DDD\Backoffice\Shared\ValueObject\Password;
use App\DDD\Backoffice\Shared\ValueObject\SpanishPhoneNumber;
use App\DDD\Backoffice\Shared\ValueObject\Text;
use App\DDD\Backoffice\Shared\ValueObject\Uuid;
use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;
use App\DTO\Booking\BookingRequestDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use App\Services\BusinessService;
use App\Services\ServiceService;
use Random\RandomException;

final readonly class PreBookingServiceV2
{

    private const BOOKING_EXPIRATION_MINS = 30;

    protected function __construct(
        private PreBookingRepositoryV2Interface $preBookingRepository,
        private BookingRepositoryInterface      $bookingRepository,
        private ServiceService                  $serviceService,
        private BusinessService                 $businessService
    )
    {

    }

    public function create(BusinessId $businessId, BookingRequestDTO $bookingRequestDTO, AuthUserId $authUserId): BookingResponseDTO
    {
        $this->serviceService->findById($businessId->value(), $bookingRequestDTO->serviceId);
        $this->businessService->assertUserCanModifyBusiness($businessId->value(), $authUserId->value());

        $payload = $bookingRequestDTO->toArray()
            + [
                'token' => $this->generateRandomToken(),
                'expiration_date' => now()->addMinutes(self::BOOKING_EXPIRATION_MINS),
            ];

        $preBooking = $this->mapPreBooking($payload, $authUserId);

        $this->preBookingRepository->create($preBooking);

        return BookingResponseDTO::createFromDDDPreBookingModel($preBooking);
    }

    private function generateRandomToken(): string
    {
        try {
            $bytes = random_bytes(20);
        } catch (RandomException) {
            return $this->generateRandomToken();
        }
        return bin2hex($bytes);
    }

    private function mapPreBooking(array $payload, AuthUserId $authUserId): PreBooking
    {
        return PreBooking::create(
            id: null,
            serviceId: ServiceId::createFromInt(($payload['service_id'])),
            authUserId: $authUserId,
            startDate: BookingDate::createfromString($payload['start_date']),
            endDate: BookingDate::createfromString($payload['end_date']),
            userName: Text::createFromString($payload['user_name']),
            userEmail: Text::createFromString($payload['user_email']),
            userPhone: SpanishPhoneNumber::createFromString($payload['user_phone']),
            userPass: Password::createFromString($payload['user_pass']),
            uuid: Uuid::crateFromString($payload['uuid']
                ?? Uuid::random()),
        );
    }
}
