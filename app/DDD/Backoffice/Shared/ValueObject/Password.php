<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\TextIsPassingMaxLenghtException;

final readonly class Password extends Text
{
    protected string $value;
    private const PASS_PATTERN = '/^(?=.*[A-Za-z])(?=.*\d).{8,}$/'; // 8 caracteres 1 letra, 1 numero

    public function __construct(string $value)
    {
        $this->ensureIsValidPassword($value);
        $this->value = $value;
    }

    private function ensureIsValidPassword(string $value) : void {

        $this->ensureLengthIsGreaterThan(8, $value);

        // todo: comprobar que pasa el regex
    }

    private function ensureLengthIsGreaterThan(int $length, string $value) : void {
        if (strlen($value) < $length) {
            throw new TextIsPassingMaxLenghtException($length);
        }
    }
}
