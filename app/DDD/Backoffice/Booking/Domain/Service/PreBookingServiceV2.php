<?php

namespace App\DDD\Backoffice\Booking\Domain\Service;

use App\DDD\Backoffice\Booking\Domain\Entity\PreBooking;
use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingToken;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\DDD\Backoffice\Shared\Domain\Entity\Mail\MailMessage;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerServiceInterface;
use App\DDD\Backoffice\Shared\ValueObject\Email;
use App\DDD\Backoffice\Shared\ValueObject\Password;
use App\DDD\Backoffice\Shared\ValueObject\SpanishPhoneNumber;
use App\DDD\Backoffice\Shared\ValueObject\Text;
use App\DDD\Backoffice\Shared\ValueObject\Uuid;
use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;
use App\DTO\Booking\BookingRequestDTO;
use App\DTO\Booking\BookingResponseDTO;
use App\Exceptions\BookingDoesntBelongToServiceException;
use App\Exceptions\BookingNotFoundException;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Services\BusinessService;
use App\Services\ServiceService;
use Random\RandomException;

final readonly class PreBookingServiceV2
{

    private const BOOKING_EXPIRATION_MINS = 30;

    public function __construct(
        private PreBookingRepositoryV2Interface $preBookingRepository,
        private BookingRepositoryInterface      $bookingRepository,
        private ServiceService                  $serviceService,
        private BusinessService                 $businessService,
        private MailerServiceInterface          $mailerService,
    )
    {

    }

    public function create(BusinessId $businessId, BookingRequestDTO $bookingRequestDTO,
                           AuthUserId $authUserId, bool $includeUser): BookingResponseDTO
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

        $data = [
            'user_name' => $preBooking->getUsername()->value(),
            'confirmation_link' => null,
            'start_date' => $preBooking->getStartDate()->value(),
            'end_date' => $preBooking->getEndDate()->value(),
        ];

        // Aqui hacer el dispatch del job
        // cómo hago un dispatch de un job?
        $this->sendConfirmationMail($preBooking->getUserEmail()->value(), $data);

        return BookingResponseDTO::createFromDDDPreBookingModel($preBooking, $includeUser);
    }

    public function findById(BusinessId $businessId, ServiceId $serviceId, BookingId $bookingId,
                             AuthUserId $authUserId, bool $includeUser): BookingResponseDTO
    {
        $serviceIdValue = $serviceId->value();
        // Esta función valida que el negocio existe y
        // que el service pertencece al business por eso la usaré
        $this->businessService->assertUserCanModifyBusiness($businessId->value(), $authUserId->value());

        $this->serviceService->findById($businessId->value(), $serviceIdValue);

        $preBooking = $this->getPreBookingModelWithUserOrFail($bookingId);

        $this->assertPreBookingBelongsToService($preBooking, $serviceIdValue);

        return BookingResponseDTO::createFromDDDPreBookingModelWithUser($preBooking, $includeUser);
    }

    public function delete(BookingId $bookingId, BusinessId $businessId, ServiceId $serviceId)
    {
        $prebooking = $this->getPreBookingModelOrFail($bookingId);
        $this->preBookingRepository->delete();
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
            serviceId: ServiceId::createFromInt(($payload['service_id'])),
            authUserId: $authUserId,
            startDate: BookingDate::createfromString($payload['start_date']),
            endDate: BookingDate::createfromString($payload['end_date']),
            userName: Text::createFromString($payload['user_name']),
            userEmail: Email::createFromString($payload['user_email']),
            userPass: Password::createFromString($payload['user_pass']),
            bookingToken: BookingToken::createFromString($payload['token']),
            expirationDate: BookingDate::createfromString($payload['expiration_date']),
            id: isset($payload['id'])
                ? BookingId::createFromInt($payload['id'])
                : null,
            uuid: isset($payload['uuid'])
                ? Uuid::createFromString($payload['uuid'])
                : Uuid::random(),
            userPhone: SpanishPhoneNumber::createFromString($payload['user_phone']),
        );
    }

    private function getPreBookingModelOrFail(BookingId $bookingId): PreBooking
    {
        $preBooking = $this->preBookingRepository->findById($bookingId);

        if (is_null($preBooking)) {
            throw new BookingNotFoundException();
        }

        return $preBooking;
    }

    private function getPreBookingModelWithUserOrFail(BookingId $bookingId): PreBooking
    {
        $preBooking = $this->preBookingRepository->findByIdWithUser($bookingId);

        if (is_null($preBooking)) {
            throw new BookingNotFoundException();
        }

        return $preBooking;
    }

    private function assertPreBookingBelongsToService(PreBooking $preBooking, int $serviceId): void
    {
        if ($preBooking->getServiceId()->value() !== $serviceId) {
            throw new BookingDoesntBelongToServiceException();
        }
    }

    private function createMail(
        string $to,
        string $subject,
        string $view,
        array $data,
    ): MailMessage {
        return MailMessage::create(
            to: $to,
            subject: $subject,
            view: $view,
            data: $data
        );
    }

    private function sendConfirmationMail(
        string $email,
        array $data,
        ?string $view = 'emails.confirm-prebooking'
    ): void {
        $mail = $this->createMail(
            to: $email,
            subject: 'Confirma tu reserva',
            view: $view,
            data: $data,
        );

        $this->mailerService->sendAsync($mail);
    }

}
