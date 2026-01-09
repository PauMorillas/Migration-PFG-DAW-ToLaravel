<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\TextIsEmptyException;
use App\DDD\Backoffice\Shared\Exception\TextIsPassingMaxLenghtException;
use App\DDD\Backoffice\Shared\Exception\TextIsPassingMinLengthException;

readonly class Text
{
    protected string $value;

    protected function __construct(string $value)
    {
        $trimmedValue = trim($value);

        // Validamos que no esté vacío
        $this->ensureIsNotEmpty($trimmedValue);
        // Validamos que no pase los rangos de la BD
        $this->ensureLengthIsGreaterThan(1, $trimmedValue);
        $this->ensureLengthIsLessThan(255, $trimmedValue);

        $this->value = $value; // Devolvemos el value original, no el trimmed
    }

    public static function createFromString(string $value): static
    {
        return new static($value);
    }

    public static function createFromInt(int $value): static
    {
        $valueAsString = (string)$value;
        return new static($valueAsString);
    }

    protected function ensureIsNotEmpty(string $value): void
    {
        if (empty($value)) {
            throw new TextIsEmptyException();
        }
    }

    protected function ensureLengthIsGreaterThan(int $minLength, string $value): void
    {
        if (strlen($value) < $minLength) {
            throw new TextIsPassingMinLengthException($minLength);
        }
    }

    protected function ensureLengthIsLessThan(int $maxLength, string $value): void
    {
        if (strlen(trim($value)) > $maxLength) {
            throw new TextIsPassingMaxLenghtException($maxLength);
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function trimmedValue(): string
    {
        return trim($this->value);
    }
}
