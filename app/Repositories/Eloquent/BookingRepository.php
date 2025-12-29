<?php

namespace App\Repositories\Eloquent;

use App\Models\PreBooking;
use App\Repositories\Contracts\BookingRepositoryInterface;

class BookingRepository implements BookingRepositoryInterface
{
    public function findById(int $bookingId): ?PreBooking
    {
        return PreBooking::query()->find($bookingId);
    }
    public function findAll(): array
    {
        // TODO: Implement findAll() method.
    }

    public function create(): PreBooking
    {
        // TODO: Implement create() method.
    }

    public function delete(PreBooking $preBooking): void
    {
        $preBooking->delete();
    }

    public function findbyToken(): ?PreBooking
    {
        // TODO: Implement findbyToken() method.
    }

    public function countOverlappingPreReserva(): ?PreBooking
    {
        // TODO: Implement countOverlappingPreReserva() method.
    }


}
