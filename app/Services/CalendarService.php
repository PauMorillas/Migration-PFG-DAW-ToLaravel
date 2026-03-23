<?php

namespace App\Services;

use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\Enums\BookingStatus;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Contracts\BusinessRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DTO\Calendar\CalendarResponse;
use Illuminate\Support\Facades\Cache;

class CalendarService
{
    public function __construct(
        private ServiceRepositoryInterface  $serviceRepository,
        private BusinessRepositoryInterface $businessRepository,
        private BookingRepositoryInterface  $bookingRepository,
        private PreBookingRepositoryV2Interface $preBookingRepository
    )
    {
    }

    public function getCalendarData(ServiceId $serviceId): CalendarResponse
    {
        // Añadimos caché para no golpear la BD en cada carga del iframe público
        $cacheKey = "calendar_data_service_{$serviceId->value()}";

        return Cache::remember($cacheKey, 3600, function () use ($serviceId) {

            $service = $this->serviceRepository->findById($serviceId->value());
            $businessId = $service->business_id;

            $business = $this->businessRepository->findById($businessId);

            $bookings = $this->bookingRepository->findAllByBusinessIdAndStatus($businessId, BookingStatus::ACTIVA);

            $preBookings = $this->preBookingRepository->findAllByBusinessId($businessId);

            return CalendarResponse::createFromModels($service, $business, $bookings, $preBookings);
        });
    }
}
