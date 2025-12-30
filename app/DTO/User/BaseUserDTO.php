<?php

namespace App\DTO\User;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class BaseUserDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        protected string $name,
        protected string $email,
        protected string $role,
        protected ?string $telephone,
    )
    {}

    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if ($this->telephone !== null) {
            $data['telephone'] = $this->telephone;
        }

        return $data;
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
