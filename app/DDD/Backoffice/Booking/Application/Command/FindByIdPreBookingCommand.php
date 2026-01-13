<?php

namespace App\DDD\Backoffice\Booking\Application\Command;

use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\DDD\Backoffice\User\Domain\ValueObject\AuthUserId;

readonly class FindByIdPreBookingCommand
{
    // Todo lo necesario para pasarle al service del findById
    protected function __construct(
        public BusinessId $businessId,
        public ServiceId  $serviceId,
        public BookingId  $bookingId,
        public AuthUserId $authUserId,
        public bool       $includeUser,
    )
    {

    }

    public static function fromPrimitives(int  $businessId, int $serviceId,
                                          int  $bookingId, int $userId,
                                          bool $includeUser): self
    {
        return new self(
            businessId: BusinessId::createFromInt($businessId),
            serviceId: ServiceId::createFromInt($serviceId),
            bookingId: BookingId::createFromInt($bookingId),
            authUserId: AuthUserId::createFromInt($userId),
            includeUser: $includeUser
        );
    }

    public function toPrimitives(): iterable
    {
        return [
            'business_id' => $this->businessId->value(),
            'service_id' => $this->serviceId->value(),
            'booking_id' => $this->bookingId->value(),
            'auth_user_id' => $this->authUserId->value(),
            'include_user' => $this->includeUser,
        ];
    }
}
