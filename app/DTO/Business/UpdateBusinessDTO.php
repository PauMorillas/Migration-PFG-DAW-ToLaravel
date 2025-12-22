<?php

namespace App\DTO\Business;

use App\Models\Business;
use App\DTO\Business\BaseBusinessDTO;

final class UpdateBusinessDTO extends BaseBusinessDTO
{
    public function __construct(
        public readonly int $businessId,
        string $name,
        string $email,
        string $phone,
        string $openHours,
        string $closeHours,
        string $openDays,
    ) {
        parent::__construct( $name, $email, $phone, $openHours, $closeHours, $openDays);
    }


    public static function createFromArray(array $data, int $businessId): self
    {
        return new self(
            $businessId,
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['open_hours'],
            $data['close_hours'],
            $data['open_days'],
        );
    }

    public static function createFromModel(Business $business): self {
        return new self(
            $business->id,
            $business->name,
            $business->email,
            $business->phone,
            $business->open_hours,
            $business->close_hours,
            $business->open_days
        );
    }

    public function toArray(): array
    {
        return parent::toArray() + [
            'business_id' => $this->businessId,
        ];
    }
}