<?php

namespace App\DTO\Calendar;

use App\Models\Service;
use App\Models\Business;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use JsonSerializable;

final readonly class CalendarResponse implements Arrayable, JsonSerializable
{
    public function __construct(
        public string $open_hours,
        public string $close_hours,
        public string $open_days,
        public array $events
    ) {}

    public static function createFromModels(Business $business, Collection $bookings, Collection $preBookings, ?Service $singleService = null): self
    {
        $events = [];

        // --- BOOKINGS ---
        foreach ($bookings as $booking) {
            // Si pasamos un servicio específico, usamos ese título.
            // Si no, usamos el título del servicio anidado en el booking.
            $serviceTitle = $singleService ? $singleService->title : ($booking->service->title ?? 'Reservado');
            $serviceId = $singleService ? $singleService->id : $booking->service_id;

            $events[] = [
                'id' => $booking->id,
                'title' => $serviceTitle,
                'start' => Carbon::parse($booking->start_date)->format('Y-m-d\TH:i:s'),
                'end' => Carbon::parse($booking->end_date)->format('Y-m-d\TH:i:s'),
                'color' => '#3B83BD',
                'business_id' => $business->id,
                'service_id' => $serviceId,
            ];
        }

        // --- PRE-BOOKINGS ---
        foreach ($preBookings as $preBooking) {
            $pbArray = is_array($preBooking) ? $preBooking : $preBooking->toArray();

            // Misma lógica para el título
            $serviceTitle = $singleService
                ? $singleService->title
                : ($pbArray['service']['title'] ?? $preBooking->service->title ?? 'Pendiente');

            $serviceId = $singleService ? $singleService->id : ($pbArray['service_id'] ?? null);

            $events[] = [
                'id' => $pbArray['id'] ?? null,
                'title' => $serviceTitle,
                'start' => Carbon::parse($preBooking->start_date)->format('Y-m-d\TH:i:s'),
                'end' => Carbon::parse($preBooking->end_date)->format('Y-m-d\TH:i:s'),
                'color' => '#F5A623',
                'business_id' => $business->id,
                'service_id' => $serviceId,
            ];
        }

        return new self(
            open_hours: $business->open_hours,
            close_hours: $business->close_hours,
            open_days: $business->open_days,
            events: $events
        );
    }

    public function toArray(): array
    {
        return [
            'open_hours' => $this->open_hours,
            'close_hours' => $this->close_hours,
            'open_days' => $this->open_days,
            'events' => $this->events,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
