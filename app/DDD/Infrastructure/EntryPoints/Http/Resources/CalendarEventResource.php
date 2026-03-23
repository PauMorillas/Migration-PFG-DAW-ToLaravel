<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // El color por defecto que tenías en Java
        $eventColor = "#3B83BD";

        // Convertimos la fecha a la zona horaria de Madrid y al formato ISO 8601 con offset
        // Ej: 2025-10-10T10:00:00+02:00
        $startInMadrid = Carbon::parse($this->fecha_inicio)->setTimezone('Europe/Madrid')->toIso8601String();
        $endInMadrid = Carbon::parse($this->fecha_fin)->setTimezone('Europe/Madrid')->toIso8601String();

        return [
            'id' => $this->id,
            'title' => $this->servicio->titulo ?? 'Reservado', // Relación con el servicio
            'start' => $startInMadrid,
            'end' => $endInMadrid,
            'color' => $eventColor,
        ];
    }
}
