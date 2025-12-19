<?php

namespace App\DTO;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseBusinessDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        protected readonly string $name,
        protected readonly string $email,
        protected readonly string $phone,
        protected readonly string $openHours,
        protected readonly string $closeHours,
        protected readonly string $openDays,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'open_hours' => $this->openHours,
            'close_hours' => $this->closeHours,
            'open_days' => $this->openDays,
        ];
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }
}