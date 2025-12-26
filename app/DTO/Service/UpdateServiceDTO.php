<?php

namespace App\DTO\Service;

use App\Models\Service;
use App\DTO\Service\BaseServiceDTO;

final class UpdateServiceDTO extends BaseServiceDTO {
    public function __construct(
        protected readonly int $businessId,
        protected readonly int $serviceId,
        string $title,
        string $description,
        string $location,
        float $price,
        int $duration,
    ) {
        parent::__construct($title, $description, $location, $price, $duration);
    }

    public static function createFromArray(array $data, int $businessId, int $serviceId): self {
        return new self(
            businessId: $businessId,
            serviceId: $serviceId,
            title: $data['title'],
            description: $data['description'],
            location: $data['location'],
            price: (float) $data['price'],
            duration: (int) $data['duration'],
        );
    }

    public static function createFromModel(Service $service): self {
        return new self(
            businessId: $service->business_id,
            serviceId: $service->id,
            title: $service->title,
            description: $service->description,
            location: $service->location,
            price: (float) $service->price,
            duration: (int) $service->duration,
        );
    }

     public function toArray(): array
    {
        return parent::toArray() + [
            'business_id' => $this->businessId,
            'service_id' => $this->serviceId,
        ];
    }
}
