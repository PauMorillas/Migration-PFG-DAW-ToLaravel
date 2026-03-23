<?php

namespace App\DTO\Calendar;

use App\Models\Booking;
use App\Models\Service;
use App\Models\Business;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use JsonSerializable;

final readonly class CalendarResponse implements Arrayable, JsonSerializable
{
    public function __construct(
        public int $serviceId,
        public int $durationMinutes,
        public string $open_hours,
        public string $close_hours,
        public string $open_days,
        public array $events
    ) {}

    public static function createFromModels(Service $service, Business $business, Collection $bookings, Collection $preBookings): self
    {
        // Unimos las reservas y las PreReservas
        $allOccupiedSlots = $bookings->concat((array)$preBookings);

        // Mapeamos los eventos al formato FullCalendar
        $events = $allOccupiedSlots->map(function ($slot) {
            return [
                'id' => $slot->id,
                'title' => $slot->service->title ?? 'Reservado',
                'start' => Carbon::parse($slot->start_date)->setTimezone('Europe/Madrid')->toIso8601String(),
                'end' => Carbon::parse($slot->end_date)->setTimezone('Europe/Madrid')->toIso8601String(),
                // Si es una prereserva la pintamos de gris en vez de azul
                'color' => isset($slot->expiration_date) ? '#F5A623' : '#3B83BD',
            ];
        })->toArray();

        return new self(
            serviceId: $service->id,
            durationMinutes: $service->duration_minutes,
            open_hours: $business->open_hours,
            close_hours: $business->close_hours,
            open_days: $business->open_days,
            events: $events);
    }

    public function toArray(): array
    {
        return [
            'serviceId' => $this->serviceId,
            'durationMinutes' => $this->durationMinutes,
            'open_hours' => $this->open_hours,
            'close_hours' => $this->close_hours,
            'open_days' => $this->open_days,
            'events' => $this->events,
        ];
    }

    public function jsonSerialize(): array {
        return $this->toArray();
    }
}
