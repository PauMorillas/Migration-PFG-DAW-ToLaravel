<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Support\Collection;
use stdClass;

interface BookingRepositoryInterface
{
    public function findById(int $bookingId): Booking;
    /**
     * @return Array<stdClass>
     * */
    public function findAllWithQueryBuilder(int $businessId): Collection;
    /**
     * @return Array<stdClass>
     * */
    public function findAll(int $businessId): Collection;
    public function update(Booking $booking, array $data): Booking;

    public function assertExists(int $bookingId): bool;
}
