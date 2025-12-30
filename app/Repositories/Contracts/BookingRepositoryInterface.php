<?php

namespace App\Repositories\Contracts;

use App\Models\PreBooking;
use Illuminate\Support\Collection;

interface BookingRepositoryInterface {
    // TODO: En principio solo necesitaré estas funciones
    public function findById(int $bookingId): ?PreBooking;
    public function findAll(int $businessId): Collection;
    public function create(array $data): PreBooking;
    public function delete(PreBooking $preBooking): void;
    public function findByToken(): ?PreBooking;
    public function countOverlappingPreReserva(): ?PreBooking; // TODO: ESTO DEVOLVERÁ UNA COLECCIÓN?
}
