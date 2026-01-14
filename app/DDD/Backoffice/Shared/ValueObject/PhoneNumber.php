<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\InvalidPhoneNumberException;

readonly class PhoneNumber extends Text
{
    // Regex: Por defecto el formato español
    protected const PATTERN = '/^\d+$/'; // Genérico: solo números

    protected function __construct(string $value)
    {
        parent::__construct($value);

        $trimmedValue = parent::trimmedValue();

        $this->ensureIsValidPhoneNumber($trimmedValue);
    }

    private function ensureIsValidPhoneNumber(string $value): void
    {
        if (!preg_match(static::PATTERN, $value)) {
            throw new InvalidPhoneNumberException('El teléfono no sigue el formato requerido');
        }
    }
}
