<?php

namespace App\Services;

use App\DDD\Backoffice\Business\Domain\ValueObject\BusinessId;
use App\DDD\Backoffice\Service\Domain\ValueObject\ServiceId;
use App\Enums\BookingStatus;
use App\Models\Business;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Contracts\BusinessRepositoryInterface;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DTO\Calendar\CalendarResponse;
use Illuminate\Support\Facades\Cache;

class CalendarService
{
    public function __construct(
        private ServiceRepositoryInterface      $serviceRepository,
        private BusinessRepositoryInterface     $businessRepository,
        private BookingRepositoryInterface      $bookingRepository,
        private PreBookingRepositoryV2Interface $preBookingRepository,
        private ServiceService $serviceService,
        private BusinessService $businessService,
    )
    {
    }

    public function getServiceCalendarData(ServiceId $serviceId): CalendarResponse
    {
        $idStr = $serviceId->value();
        $cacheKey = "calendar_data_service_{$idStr}";

        return Cache::remember($cacheKey, 3600, function () use ($idStr) {

            $this->serviceService->assertExists($idStr);
            $service = $this->serviceRepository->findById($idStr);

            $businessId = BusinessId::createFromInt($service->business_id);
            $business = $this->businessRepository->findById($businessId->value());

            $bookings = $this->bookingRepository->findAllByBusinessIdAndStatus($businessId->value(), BookingStatus::ACTIVA);

            $preBookings = $this->preBookingRepository->findAllByBusinessId($businessId);

            return CalendarResponse::createFromModels($business, $bookings, $preBookings, $service);
        });
    }

    public function getBusinessCalendarData(BusinessId $businessId): CalendarResponse
    {
        $idStr = $businessId->value();
        $cacheKey = "calendar_data_business_{$idStr}";

        return Cache::remember($cacheKey, 3600, function () use ($businessId, $idStr) {

            $this->businessService->assertExists($idStr);
            $business = $this->businessRepository->findById($idStr);

            $bookings = $this->bookingRepository->findAllByBusinessIdAndStatus($idStr, BookingStatus::ACTIVA);

            $preBookings = $this->preBookingRepository->findAllByBusinessId($businessId);

            // Mapeamos SIN PASAR SERVICIO (El objeto de respuesta lo extraerá de las reservas)
            return CalendarResponse::createFromModels($business, $bookings, $preBookings, null);
        });
    }
}
