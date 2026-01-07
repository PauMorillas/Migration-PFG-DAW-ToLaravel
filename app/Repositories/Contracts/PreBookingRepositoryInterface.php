<?php

namespace App\Repositories\Contracts;

use App\Models\PreBooking;
use Illuminate\Support\Collection;

interface PreBookingRepositoryInterface {
    // TODO: En principio solo necesitaré estas funciones
    public function findById(int $bookingId): ?PreBooking;
    public function findAll(int $businessId): Collection;
    public function create(array $data): PreBooking;
    public function delete(PreBooking $preBooking): void;
    public function findByToken(string $token): ?PreBooking;
    public function countOverlappingPreBookings(int $serviceId, string $startDate, string $endDate, ?int $ignorePreBookingId): ?int;
}
