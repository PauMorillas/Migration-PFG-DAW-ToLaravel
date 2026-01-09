<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\InvalidAppDateException;
use Carbon\Carbon;

abstract readonly class Date
{
    // Define el formato estándar de la aplicación,
    // pero se permite sobrescribirlo (protected)
    protected const FORMAT = 'Y-m-d';
    protected Carbon $value;

    protected function __construct(string|Carbon $date)
    {
        // Si es Carbon, lo convertimos a string usando el formato de la clase
        // para pasarle la "prueba de fuego" de la validación.
        $dateAsString = $date instanceof Carbon
            ? $date->format(static::FORMAT)
            : $date;

        $this->ensureIsValidDate($dateAsString);
        $this->value = Carbon::createFromFormat(static::FORMAT, $dateAsString);
    }

    public static function createFromString(string $date): static
    {
        return new static($date);
    }

    public static function createFromCarbon(Carbon $date): static
    {
        return new static($date);
    }

    private function ensureIsValidDate(string $date): void
    {
        $formattedDate = Carbon::createFromFormat(static::FORMAT, $date);

        if (is_null($formattedDate) || $formattedDate->format(static::FORMAT) !== $date) {
            throw new InvalidAppDateException("La fecha $date no coincide con el formato " . static::FORMAT);
        }
    }

    public function value(): string
    {
        return $this->value->format(static::FORMAT);
    }

    public function valueAsCarbon(): Carbon
    {
        // Devolvemos una copia para asegurar la inmutabilidad
        return $this->value->copy();
    }
}
