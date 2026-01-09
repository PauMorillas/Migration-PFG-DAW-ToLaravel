<?php

namespace App\DDD\Backoffice\Service\Domain\ValueObject;

use App\DDD\Backoffice\Shared\ValueObject\Id;

final readonly class ServiceId extends Id
{
    protected function __construct(int $value)
    {
        parent::__construct($value);
    }
}
