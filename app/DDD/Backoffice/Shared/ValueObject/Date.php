<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use Carbon\Carbon;
use Carbon\Exceptions\InvalidDateException;
use Exception;

abstract readonly class Date
{
    // Define el formato estándar de la aplicación,
    // pero se permite sobrescribirlo (protected)
    protected const FORMAT = 'Y-m-d';
    protected Carbon $value;

    public function __construct(string $date) {
        $this->ensureIsValidDate($date);
        $this->value = Carbon::createFromFormat(static::FORMAT, $date);
    }

    private function ensureIsValidDate(string $date): void {
        try {
            $formatedDate = Carbon::createFromFormat(static::FORMAT, $date);

            if (is_null($formatedDate) || $formatedDate->format(static::FORMAT) !== $date) {
                // TODO: EXCEPCIONES PROPIAS DE DOMINIO
                throw new InvalidDateException($date, 'La fecha no tiene un formato válido');
            }
        } catch (Exception $ex) {
            throw new InvalidDateException($ex->getMessage(), $ex->getCode());
        }
    }

    public function getValue(): string {
        return $this->value->format(static::FORMAT);
    }

    public function getValueAsCarbon(): Carbon {
        return $this->value;
    }
}
