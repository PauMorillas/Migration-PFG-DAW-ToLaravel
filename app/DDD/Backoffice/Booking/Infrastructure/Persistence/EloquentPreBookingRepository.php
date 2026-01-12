<?php

namespace App\DDD\Backoffice\Booking\Infrastructure\Persistence;

use App\DDD\Backoffice\Booking\Domain\Entity\PreBooking;
use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingDate;
use App\DDD\Backoffice\Booking\Domain\ValueObject\BookingId;
use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

final readonly class EloquentPreBookingRepository implements PreBookingRepositoryV2Interface
{
    public function findById(BookingId $bookingId): ?PreBooking
    {
        $model = PreBooking::getEloquentModel()->newQuery()->find($bookingId->value());

        if (!is_null($model)) {
            return PreBooking::fromEloquentModel($model);
        }

        return null;
    }

    public function findAll(BusinessId $businessId): array
    {
        return DB::table('pre_bookings')
            ->join('services', 'services.id', '=', 'pre_bookings.service_id')
            ->where('services.business_id', $businessId)
            ->select('pre_bookings.*')
            ->get()
            ->map(function (object $row) {
                return PreBooking::fromEloquentModel(
                    $this->hydrateModel($row)
                );
            })
            ->all();
    }

    public function create(PreBooking $preBooking): PreBooking
    {
        $model = $preBooking->toEloquentModel();
        $model->save();

        return PreBooking::fromEloquentModel($model);
    }

    public function delete(PreBooking $preBooking): void
    {
        $model = $preBooking->toEloquentModel();
        $model->delete();
    }

    public function findByToken(string $token): ?PreBooking
    {
        $model = PreBooking::getEloquentModel()
            ->newQuery()
            ->where('token', $token)
            ->first();

        return $model
            ? PreBooking::fromEloquentModel($model)
            : null;
    }

    public function countOverlappingPreBookings(ServiceId    $serviceId,
                                                BookingDate $startDate,
                                                BookingDate $endDate,
                                                ?BookingId   $ignorePreBookingId): ?int
    {
        $query = DB::table('pre_bookings')
            ->where('service_id', $serviceId->value())
            ->where('start_date', '<', $endDate->value())
            ->where('end_date', '>', $startDate->value())
            // No contamos con las PreReservas expiradas ni con las eliminadas
            ->where('expiration_date', '>', now()->subMinute())
            ->whereNull('deleted_at');

        if ($ignorePreBookingId) {
            $query->where('id', '!=', $ignorePreBookingId->value());
        }

        return $query->count();
    }

    private function hydrateModel(object $row): Model
    {
        $model = PreBooking::getEloquentModel();
        $model->forceFill((array)$row);
        $model->exists = true;

        return $model;
    }
}
