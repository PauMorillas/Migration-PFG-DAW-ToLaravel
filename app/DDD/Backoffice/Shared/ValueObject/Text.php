<?php

namespace App\DDD\Backoffice\Shared\ValueObject;

use App\DDD\Backoffice\Shared\Exception\TextIsEmptyException;
use App\DDD\Backoffice\Shared\Exception\TextIsPassingMaxLenghtException;

readonly class Text
{
    protected string $value;

    public function __construct(string $value) {
        if (empty($value)) {
            throw new TextIsEmptyException();
        }

        if (strlen($value) > 255) {
            throw new TextIsPassingMaxLenghtException();
        }

        $this->value = $value;
    }
}
