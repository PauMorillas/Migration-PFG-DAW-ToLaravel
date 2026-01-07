<?php

namespace App\Repositories\Eloquent;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use function Laravel\Prompts\table;

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

    public function findAllByBusinessId(int $businessId): Collection
    {
        // Eloquent ya sabe que id es el del usuario por los métodos
        // de las relaciones que se definen en el modelo
        return Booking::with(['user', 'service'])
            ->whereHas('service', function ($query) use ($businessId) {
                $query->where('business_id', $businessId);
            })
            ->get();
    }

    public function findById(int $bookingId): ?Booking
    {
        return Booking::query()->find($bookingId);
    }

    public function findByIdWithUser(int $bookingId): ?Booking
    {
        return Booking::query()->with('user')->find($bookingId);
    }

    public function create(array $data): Booking
    {
        return Booking::query()->create($data);
    }

    public function updateBookingStatus(Booking $booking, array $data): Booking
    {
        $booking->update($data);
        return $booking;
    }

    public function assertExists(int $bookingId): bool
    {
        return Booking::query()->where('id', $bookingId)->exists();
    }

    public function countOverlappingBookings(int $serviceId, string $startDate, string $endDate, ?int $ignoreBookingId = null, ?BookingStatus $status = BookingStatus::ACTIVA): int
    {
        $query = DB::table('bookings')
            ->where('service_id', $serviceId)
            ->where('start_date', '<', $endDate)
            ->where('end_date', '>', $startDate)
            ->where('status', '=', $status->value)
            // No contaremos las que fueron eliminadas
            ->where('deleted_at', '=', null);

        if ($ignoreBookingId !== null) {
            $query->where('id', '!=', $ignoreBookingId);
        }

        return $query->count();
    }
}
