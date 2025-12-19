<?php
namespace App\Services;

use App\DTO\Service\UpdateServiceDTO;
use App\Models\Service;
use App\DTO\Service\CreateServiceDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Eloquent\BusinessRepository;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class ServiceService
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly BusinessRepository $businessRepository,
    ) {
    }

    public function findAll(int $businessId): Collection
    {
        $this->businessRepository->findById($businessId); // Primero se valida la existencia

        return $this->serviceRepository->findAll($businessId);
    }

    public function findById(int $businessId, $serviceId): Service
    {
        return $this->serviceRepository->findById($businessId, $serviceId);
    }

    // Hacer DTO
    public function create(CreateServiceDTO $dto): Service
    {
        // REGLA DE NEGOCIO, para crear un servicio se debe tener primero un negocio asociado
        $this->businessRepository->findById($dto->businessId);
        
        return $this->serviceRepository->create($dto->toArray());
    }

    public function update(UpdateServiceDTO $dto): Service
    {
        $service = $this->serviceRepository->findById($dto->businessId, $dto->serviceId);

        return $this->serviceRepository->update($service, $dto->toArray());
    }

    public function delete(int $businessId, int $serviceId): bool
    {
        $service = $this->serviceRepository->findById($businessId, $serviceId);
        $this->serviceRepository->delete($service);

        return true;
    }
}