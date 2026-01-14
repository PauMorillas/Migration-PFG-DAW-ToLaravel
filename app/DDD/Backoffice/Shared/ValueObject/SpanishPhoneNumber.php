<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\InvalidPhoneNumberException;

final readonly class SpanishPhoneNumber extends PhoneNumber
{
    // Regex: 9 dígitos empezando por 6, 7, 8, o 9
    protected const PATTERN = '/^[6789]\d{8}$/';

    protected function __construct(string $value)
    {
        try {
            parent::__construct($value);
        } catch (InvalidPhoneNumberException $ex) {
            throw new InvalidPhoneNumberException('El numero de teléfono debe seguir el formato español');
        }
    }
}
