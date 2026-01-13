<?php

namespace App\DDD\Backoffice\Booking\Domain\ValueObject;

use App\DDD\Backoffice\Shared\ValueObject\Text;
use Random\RandomException;

final readonly class BookingToken extends Text
{
    protected string $value;

    protected function __construct(string $value)
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
            return $this->generateRandomToken();
        }
        return new self(bin2hex($bytes));
    }

    public function value(): string
    {
        return $this->value;
    }

}
