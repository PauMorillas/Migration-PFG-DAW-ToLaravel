<?php

namespace App\Repositories\Eloquent;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BookingRepository implements BookingRepositoryInterface
{

    public function findAllWithQueryBuilder(int $businessId): Collection
    {
        return DB::table('bookings')
            ->join('services', 'bookings.service_id', '=', 'services.id')
            ->join('users', 'bookings.user_id', '=', 'users.id')
            ->where('services.business_id', '=', $businessId)
            ->select('bookings.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.phone as user_phone')
            ->get();
    }

    public function findAll(int $businessId): Collection
    {
        // Eloquent ya sabe que id es el del usuario por los métodos
        // de las relaciones que se definen en el modelo
        return Booking::with(['user', 'service'])
            ->whereHas('service', function ($query) use ($businessId) {
                $query->where('business_id', $businessId);
            })
            ->get();
    }

    public function update(Booking $booking, array $data): Booking
    {
        // TODO: Implement update() method.
    }

    public function findById(int $bookingId): Booking
    {
        return Booking::query()->find($bookingId);
    }

    public function assertExists(int $bookingId): bool
    {
        return Booking::query()->where('id', $bookingId)->exists();
    }
}
