<?php

namespace App\DDD\Backoffice\Booking\Application\Command;

use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\DDD\Backoffice\Shared\ValueObject\Email;
use App\DDD\Backoffice\Shared\ValueObject\Password;
use App\DDD\Backoffice\Shared\ValueObject\PhoneNumber;
use App\DDD\Backoffice\Shared\ValueObject\SpanishPhoneNumber;
use App\DDD\Backoffice\Shared\ValueObject\Text;
use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;

readonly class CreatePreBookingCommand
{
    protected function __construct(
        public BusinessId $businessId,
        public ServiceId $serviceId,
        public AuthUserId $authUserId,
        public BookingDate $startDate,
        public BookingDate $endDate,
        public Text $userName,
        public Email $userEmail,
        public SpanishPhoneNumber $userPhone,
        public Password $userPass,
        public bool $includeUser,
    )
    {
    }

    public static function fromPrimitives(
        int $businessId,
        int $serviceId,
        int $authUserId,
        array $data, // TODO: Esto sería un payload ( BusinessRequestDTO )
        bool $includeUser,
    ): self {
        return new self (
            businessId: BusinessId::createFromInt($businessId),
            serviceId: ServiceId::createFromInt($serviceId),
            authUserId: AuthUserId::createFromInt($authUserId),
            startDate:  BookingDate::createFromString($data['start_date']),
            endDate:  BookingDate::createFromString($data['end_date']),
            userName: Text::createFromString($data['user_name']),
            userEmail:  Email::createFromString($data['user_email']),
            userPhone:  SpanishPhoneNumber::createFromString($data['user_phone']),
            userPass: Password::createFromString($data['user_pass']),
            includeUser: $includeUser,
        );
    }

    public function toPrimitives(): iterable {
        return [
            'business_id' => $this->businessId->value(),
            'service_id' => $this->serviceId->value(),
            'auth_user_id' => $this->authUserId->value(),
            'start_date' => $this->startDate->value(),
            'end_date'    => $this->endDate->value(),
            'user_name'   => $this->userName->value(),
            'user_email'  => $this->userEmail->value(),
            'user_phone'  => $this->userPhone->value(),
            'user_pass'   => $this->userPass->value(),
        ];
    }
}
