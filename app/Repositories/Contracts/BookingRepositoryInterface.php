<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Support\Collection;
use stdClass;

interface BookingRepositoryInterface
{
    public function findById(int $bookingId): ?Booking;
    public function findByIdWithUser(int $bookingId): ?Booking;
    /**
     * @return Array<stdClass>
     * */
    public function findAllWithQueryBuilder(int $businessId): Collection;
    /**
     * @return Array<stdClass>
     * */
    public function findAllByBusinessId(int $businessId): Collection;
    public function create(array $data): Booking;
    public function updateBookingStatus(Booking $booking, array $data): Booking;
    public function assertExists(int $bookingId): bool;
}
