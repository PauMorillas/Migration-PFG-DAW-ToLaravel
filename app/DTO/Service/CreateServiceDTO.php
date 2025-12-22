<?php 

namespace App\DTO\Service;

use App\Models\Service;
use App\DTO\Service\BaseServiceDTO;

final class CreateServiceDTO extends BaseServiceDTO {
    public function __construct(
        public readonly int $businessId,
        string $title,
        string $description,
        string $location,
        float $price,
        int $duration,
    ) {
        parent::__construct($title, $description, $location, $price, $duration);
    }

     public static function createFromArray(array $data, int $businessId): self
    {
        return new self(
            businessId: $businessId,
            title: $data['title'],
            description: $data['description'],
            location: $data['location'],
            price: (float) $data['price'],
            duration: (int) $data['duration'],
        );
    }

    public static function createFromModel(Service $service): self
    {
        return new self(
            businessId: $service->business_id,
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
        ];
    }
}