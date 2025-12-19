<?php
namespace App\Services;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Eloquent\BusinessRepository;
use Illuminate\Database\Eloquent\Collection;

class ServiceService
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly BusinessRepository $businessRepository,
    ) {
    }

    public function findAll(int $id): Collection
    {
        $this->businessRepository->findById($id); // Primero se valida la existencia

        return $this->serviceRepository->findAll($id);
    }

    public function findById(int $id, $serviceId): Service
    {
        return $this->serviceRepository->findById($id, $serviceId);
    }

    // Hacer DTO
    public function create(int $businessId, array $data): Service
    {
        // REGLA DE NEGOCIO, para crear un servicio se debe tener primero un negocio asociado
        $business = $this->businessRepository->findById($businessId);
        $data['business_id'] = $business->id; // TODO: Rehacer quitando este paso
        
        return $this->serviceRepository->create($data);
    }

    public function update(int $id, int $serviceId, array $data): Service
    {
        unset($data['business_id']); // No se debe permitir actualizar el negocio
        $service = $this->serviceRepository->findById($id, $serviceId);

        return $this->serviceRepository->update($service, $data);
    }

    public function delete(int $id, int $serviceId): bool
    {
        $service = $this->serviceRepository->findById($id, $serviceId);
        $this->serviceRepository->delete($service);

        return true;
    }
}