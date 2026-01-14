<?php

namespace App\DDD\Backoffice\Booking\Domain\Repository;

use App\DDD\Backoffice\Booking\Domain\Entity\PreBooking;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use Illuminate\Support\Collection;

interface PreBookingRepositoryV2Interface
{
    // TODO: En principio solo necesitaré estas funciones
    public function findById(BookingId $bookingId): ?PreBooking;
    /** @return PreBooking[] */
    public function findAll(BusinessId $businessId): array;
    public function create(PreBooking $preBooking): PreBooking;
    public function delete(PreBooking $preBooking): void;
    public function findByToken(string $token): ?PreBooking;
    public function countOverlappingPreBookings(ServiceId $serviceId, BookingDate $startDate,
                                                BookingDate $endDate, ?BookingId $ignorePreBookingId): ?int;
}
