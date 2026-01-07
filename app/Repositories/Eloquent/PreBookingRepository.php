<?php

namespace App\Repositories\Eloquent;

use App\Models\PreBooking;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PreBookingRepository implements PreBookingRepositoryInterface
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

    public function findByToken(string $token): ?PreBooking
    {
        return PreBooking::query()
            ->where('token', $token)
            ->first();
    }

    public function countOverlappingPreBookings(int $serviceId,
                                               string $startDate,
                                               string $endDate,
                                               ?int $ignorePreBookingId): ?int
    {
        $query = DB::table('pre_bookings')
            ->where('service_id', $serviceId)
            ->where('start_date', '<' , $endDate)
            ->where('end_date', '>' , $startDate)
            // No contamos con las PreReservas expiradas ni con las eliminadas
            ->where('expiration_date', '>', now())
            ->whereNull('deleted_at');

        if ($ignorePreBookingId) {
            $query->where('id', '!=', $ignorePreBookingId);
        }

        return $query->count();
    }

}
