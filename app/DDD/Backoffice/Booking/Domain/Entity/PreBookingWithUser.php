<?php

namespace App\DDD\Backoffice\Booking\Domain\Entity;

use App\Models\User;

final class PreBookingWithUser
{
    protected PreBooking $preBooking;
    protected User $user;

    protected function __construct(
        PreBooking $preBooking,
        User $user,
    )
    {
        $this->preBooking = $preBooking;
        $this->user = $user;
    }

    public static function create(PreBooking $preBooking, User $user): PreBookingWithUser
    {
        return new self($preBooking, $user);
    }

    protected function getUserData() {
        return [
            $this->user
        ];
    }
}
