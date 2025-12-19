<?php

namespace App\DTO\Service;

use JsonSerializable;
use Illuminate\Contracts\Support\Arrayable;

abstract class BaseServiceDTO implements Arrayable, JsonSerializable
{
    public function __construct(
        protected readonly string $title,
        protected readonly string $description,
        protected readonly string $location,
        protected readonly float $price,
        protected readonly int $duration,
    ) {
    }
// No lo implementarán los hijos ya que en el update necesitaré ambos ids y en el create solo el del negocio
// REGLA: El padre solo define la estructura común de los DTO, no fábricas
/*     abstract public static function createFromArray(array $data, int $businessId): static;
 */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'location' => $this->location,
            'price' => $this->price,
            'duration' => $this->duration,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}