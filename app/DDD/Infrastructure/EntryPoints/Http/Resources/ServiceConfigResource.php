<?php

namespace App\DDD\Infrastructure\EntryPoints\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // TODO: Deberás asegurarte de que tu interfaz en Angular consuma 'durationMinutes' y no 'duracionMinutos'.
        return [
            'serviceId' => $this->id,
            'durationMinutes' => $this->duracion_minutos,
            'openingTime' => $this->negocio->hora_apertura,
            'closingTime' => $this->negocio->hora_cierre,
            'openingDays' => $this->negocio->dias_apertura,
        ];
    }
}
