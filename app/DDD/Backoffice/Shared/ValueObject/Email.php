<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\EmailFormatIsNotValidException;

class Email
{
    protected string $value;
    public function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new EmailFormatIsNotValidException();
        }
    }
}
