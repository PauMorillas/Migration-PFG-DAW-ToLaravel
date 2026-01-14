<?php

namespace App\DDD\Backoffice\Business\Domain\ValueObject;

use App\DDD\Backoffice\Shared\ValueObject\Id;

final readonly class BusinessId extends Id
{
    protected function __construct(int $value)
    {
        parent::__construct($value);
    }
}
