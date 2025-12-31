<?php

namespace App\DTO\User;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

readonly class UserResponseDTO implements Arrayable, JsonSerializable
{
    public function __construct(public int      $id,
                                private string  $name,
                                private string  $email,
                                private ?string $telephone = null)
    {

    }

    public static function createFromModel(User $user): self
    {
        return new self(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            telephone: $user->telephone,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'telephone' => $this->telephone,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
