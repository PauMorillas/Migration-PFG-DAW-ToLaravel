<?php

namespace App\DDD\Backoffice\Booking\Domain\ValueObject;

use App\DDD\Backoffice\Shared\ValueObject\Text;
use Random\RandomException;

// TODO: VAS POR AKI
final readonly class BookingToken extends Text
{
    protected string $value;

    private function __construct(string $value)
    {
        parent::__construct($value);
    }

    public static function generate(): self
    {
        return new self(static::generateRandomToken()->value());
    }

    private function generateRandomToken(): self
    {
        try {
            $bytes = random_bytes(20);
        } catch (RandomException) {
            return new self($this->generateRandomToken()->value());
        }
        return new self(bin2hex($bytes));
    }

    public function value(): string
    {
        return $this->value;
    }

}
