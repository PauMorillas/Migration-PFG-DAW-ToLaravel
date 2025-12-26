<?php

namespace App\DTO\User;

use App\DTO\User\BaseUserDTO;
use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

class CreateUserDTO extends BaseUserDTO implements Arrayable, JsonSerializable
{

    public function __construct(
        protected string $name,
        protected string $email,
        protected readonly string $password,
        protected string $role
    )
    {
        parent::__construct($name, $email, $role);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'], // Aquí si existe
            role: $data['role'],
        );
    }

    public function createFromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            password: null, // El pass nunca lo mostraremos
            role: $user->role,
        );
    }

    public function toArray(): array {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
