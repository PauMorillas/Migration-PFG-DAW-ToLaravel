<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\PasswordIsWeakException;
use App\DDD\Backoffice\Shared\Exception\TextIsPassingMaxLenghtException;

final readonly class Password extends Text
{
    // 8 caracteres 1 letra, 1 numero
    private const PASS_PATTERN = '/^(?=.*[A-Za-z])(?=.*\d).{8,}$/';

    protected function __construct(string $value)
    {
        $this->ensureIsValidPassword($value);
        // Validaciones de text, el padre asigna
        parent::__construct($value);
    }

    private function ensureIsValidPassword(string $value) : void {
        $this->ensureLengthIsGreaterThan(8, $value);

        if (!preg_match(self::PASS_PATTERN, $value)) {
            throw new PasswordIsWeakException();
        }
    }
}
