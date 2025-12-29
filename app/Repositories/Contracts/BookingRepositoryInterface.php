<?php

namespace App\Repositories\Contracts;

use App\Models\PreBooking;

interface BookingRepositoryInterface {
    // TODO: En principio solo necesitaré estas funciones
    public function findById(int $bookingId): ?PreBooking;
    public function findAll(): array;
    public function create(): PreBooking;
    public function delete(PreBooking $preBooking): void;
    public function findbyToken(): ?PreBooking;
    public function countOverlappingPreReserva(): ?PreBooking; // TODO: ESTO DEVOLVERÁ UNA COLECCIÓN?
}
