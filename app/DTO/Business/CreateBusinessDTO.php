<?php

namespace App\DTO\Business;

use App\Models\Business;

final class CreateBusinessDTO extends BaseBusinessDTO
{
    public function __construct(
        protected readonly ?int $businessId, // TODO: QUITAR YA! ANTERIOR: Lo hice asi por no complicarlo más
        // pero podemos hacerlo mejor creando un objeto de Respuesta
        string                  $name,
        string                  $email,
        string                  $phone,
        string                  $openHours,
        string                  $closeHours,
        string                  $openDays,
        protected readonly int  $userId
    )
    {
        parent::__construct($name, $email, $phone, $openHours, $closeHours, $openDays);
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            null,
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['open_hours'],
            $data['close_hours'],
            $data['open_days'],
            $data['user_id']
        );
    }

    public static function createFromModel(Business $business): self
    {
        return new self(
            $business->id,
            $business->name,
            $business->email,
            $business->phone,
            $business->open_hours,
            $business->close_hours,
            $business->open_days,
            $business->user_id
        );
    }

    public function toArray(): array
    {
        return parent::toArray() + [
                'business_id' => $this->businessId,
                'user_id' => $this->userId,
            ];
    }
}
