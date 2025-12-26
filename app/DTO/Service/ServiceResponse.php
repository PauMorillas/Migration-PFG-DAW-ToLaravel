<?php

namespace App\DTO\Service;

use App\Models\Service;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

readonly class ServiceResponse implements Arrayable, JsonSerializable
{

    public function __construct(public readonly int    $serviceId,
                                public readonly int    $businessId,
                                public readonly string $title,
                                public readonly string $description,
                                public readonly string $location,
                                public readonly float  $price,
                                public readonly string $duration)
    {
    }

    public
    static function createFromModel(Service $service): self
    {
        return new self(
            serviceId: $service->id,
            businessId: $service->business_id,
            title: $service->title,
            description: $service->description,
            location: $service->location,
            price: $service->price,
            duration: $service->duration,
        );
    }

    public
    function toArray(): array
    {
        return [
            'service_id' => $this->serviceId,
            'business_id' => $this->businessId,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'duration' => $this->duration,
        ];
    }

    public
    function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
