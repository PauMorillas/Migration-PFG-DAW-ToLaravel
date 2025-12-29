<?php

namespace App\DTO\Business;

use App\Models\Business;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

readonly class BusinessResponse implements Arrayable, JsonSerializable
{
    public function __construct(public int $id,
                                public string $name,
                                public string $email,
                                public string $phone,
                                public string $open_hours,
                                public string $close_hours,
                                public int $user_id)
    {
    }

    public static function createFromModel(Business $business): self
    {
        return new self(
            id: $business->id,
            name: $business->name,
            email: $business->email,
            phone: $business->phone,
            open_hours: $business->open_hours,
            close_hours: $business->close_hours,
            user_id: $business->user_id
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'open_hours' => $this->open_hours,
            'close_hours' => $this->close_hours,
            'user_id' => $this->user_id,
        ];
    }

    public function jsonSerialize(): array {
        return $this->toArray();
    }
}
