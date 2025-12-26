<?php

namespace App\DTO\User;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

abstract class BaseUserDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $role,
    )
    {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function isGerente(): bool
    {
        return $this->role === 'GERENTE';
    }

    public function isCliente(): bool
    {
        return $this->role === 'CLIENTE';
    }
}
