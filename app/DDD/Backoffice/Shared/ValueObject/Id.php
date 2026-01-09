<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\InvalidIdException;

abstract readonly class Id
{
    // TODO: REFACTOR A LOS INHERITORS
    protected function __construct(protected int $value)
    {
        $this->ensureIsValidId($value);
    }

    public static function createFromInt(int $value): static
    {
        return new static($value);
    }

    private function ensureIsValidId(int $value): void
    {
        // Se asume que son ids autoincrementales(empiezan en 1)
        if ($value <= 0) {
            throw new InvalidIdException();
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(Id $other): bool
    {
        return $this->value === $other->value() && static::class === get_class($other);
    }
}
