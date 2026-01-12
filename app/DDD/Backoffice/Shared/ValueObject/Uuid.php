<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\InvalidUuidException;
use Random\RandomException;

final readonly class Uuid
{
    private string $value;

    private const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/';

    protected function __construct(string $value)
    {

        $normalized = $this->normalizeValue($value);

        $this->ensureIsValidUuid($normalized);

        $this->value = $normalized;
    }

    public static function crateFromString(string $value): self
    {
        return new static($value);
    }

    private function ensureIsValidUuid(string $value): void
    {
        if (!preg_match(static::UUID_PATTERN, $value)) {
            throw new InvalidUuidException($value);
        }
    }

    private function normalizeValue(string $value): string
    {
        return strtolower($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function random(): static
    {
        return new static(self::generateV4());
    }

    private static function generateV4(): string
    {
        try {
            $data = random_bytes(16);
        } catch (RandomException $e) {
            static::generateV4();
        }

        // version 4
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
        // variant RFC 4122
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
