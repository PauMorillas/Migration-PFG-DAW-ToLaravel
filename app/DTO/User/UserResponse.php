<?php

namespace App\DTO\User;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

// todo: Añadir el teléfono a la entidad
readonly class UserResponse implements Arrayable, JsonSerializable
{
    public function __construct(public int $id,
                                public string $name,
                                public string $email)
    {

    }
    public static function createFromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
