<?php 

namespace App\DTO;

use JsonSerializable;
use App\Models\Service;
use Illuminate\Contracts\Support\Arrayable;

//TODO: ELIMINAR ESTA IDEA INICIAL
final Class ServiceDTO implements Arrayable, JsonSerializable {
    public function __construct(
        public readonly int $business_id,
        public readonly ?int $service_id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $location,
        public readonly float $price,
        public readonly int $duration) {}

    public static function createFromArray(array $data, int $businessId): self {
        return new self(
            $data['business_id'],
            $data['title'],
            $data['description'],
            $data['location'],
            $data['price'],
            $data['duration']
        );
    }

    public static function createFromModel(Service $service): self {
        return new self(
            $service['business_id'],
            $service['title'],
            $service['description'],
            $service['location'],
            $service['price'],
            $service['duration']
        );
    }

    public function toArray(): array {
        return [
            'business_id' => $this->business_id,
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'duration' => $this->duration,
        ];
    }

    public function jsonSerialize(): array {
        return $this->toArray();
    }
}