<?php

namespace App\DDD\Backoffice\User\Domain\ValueObject;

use App\DDD\Backoffice\Shared\ValueObject\Id;

final readonly class AuthUserId extends Id
{
    protected function __construct(int $value)
    {
        parent::__construct($value);
    }
}
