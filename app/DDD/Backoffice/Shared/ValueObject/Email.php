<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\EmailFormatIsNotValidException;
use App\DDD\Backoffice\Shared\ValueObject\Text;

readonly class Email extends Text
{
    protected function __construct(string $value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new EmailFormatIsNotValidException();
        }

        parent::__construct($value);
    }
}
