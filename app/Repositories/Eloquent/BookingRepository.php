<?php

namespace App\Repositories\Eloquent;

use App\Models\PreBooking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{
    public function findById(int $bookingId): ?PreBooking
    {
        return PreBooking::query()->find($bookingId);
    }

    public function findAll(int $businessId): Collection
    {
        return DB::table('pre_bookings')
            ->join('services', 'services.id', '=', 'pre_bookings.service_id')
            ->where('services.business_id', $businessId)
            ->select('pre_bookings.*')
            ->get();
    }

    public function create(array $data): PreBooking
    {
        return PreBooking::query()->create($data);
    }

    public function delete(PreBooking $preBooking): void
    {
        $preBooking->delete();
    }

    public function findByToken(): ?PreBooking
    {
        // TODO: Implement findbyToken() method.
    }

    public function countOverlappingPreReserva(): ?PreBooking
    {
        // TODO: Implement countOverlappingPreReserva() method.
    }


}
