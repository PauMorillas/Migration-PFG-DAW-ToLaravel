<?php

namespace App\Services;

use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Support\Collection;

readonly class BookingService
{
    public function __construct(private BookingRepositoryInterface $bookingRepository)
    {
    }

    public function findAll(int $businessId, int $serviceId, int $userId): array {
        // TODO: VALIDACIONES

        $bookings = $this->bookingRepository->findAll($businessId);

        // todo vas por aki
        // $bookingsResp = $bookings->map();

        return [];
    }
}
