<?php

namespace App\DTO\User;

use App\Models\User;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;
use stdClass;

readonly class UserResponseDTO implements Arrayable, JsonSerializable
{
    public function __construct(public ?int     $id,
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

    // Crea un objeto de User a través de las columnas de la tabla PreBookings
    public static function createFromStdClass(stdClass $row): self
    {
        return new self(
            id: $row->user_id,
            name: $row->user_name,
            email: $row->user_email,
            telephone: $row->user_phone,
        );
    }

    // Cuando viene de una preBooking
    public static function createFromPreBooking(
        string  $name,
        string  $email,
        ?string $phone
    ): self
    {
        return new self(
            id: null,
            name: $name,
            email: $email,
            telephone: $phone,
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
