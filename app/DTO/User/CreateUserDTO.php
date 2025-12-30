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
        protected string $password,
        protected string $role,
        protected ?string $telephone = null,
    )
    {
        parent::__construct($name, $email, $role, $telephone);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            email: $data['email'],
            password: $data['password'], // Aquí sí existe
            role: $data['role'],
            telephone: array_key_exists('telephone', $data)
                ? $data['telephone']
                : null,
        );
    }

    /*
     * Esto ahora lo hacen los objetos de respuesta
     * public function createFromModel(User $user): self
    {
        return new self(
            name: $user->name,
            email: $user->email,
            password: null, // El pass nunca lo mostraremos
            role: $user->role,
        );
    }*/

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
