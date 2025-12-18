<?php
namespace App\Services;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Eloquent\BusinessRepository;
use Illuminate\Database\Eloquent\Collection;

class ServiceService {

    private readonly ServiceRepositoryInterface $serviceRepository;
    private readonly BusinessRepository $businessRepository;

    public function __construct(ServiceRepositoryInterface $serviceRepository, BusinessRepository $businessRepository)
    {
        $this->serviceRepository = $serviceRepository;
        $this->businessRepository = $businessRepository;
    }

    public function findAll(int $id): Collection {
        $this->businessRepository->findById($id); // Primero se valida la existencia

        return $this->serviceRepository->findAll($id);
    }

    public function findById(int $id, $serviceId): Service{
        return $this->serviceRepository->findById($id, $serviceId);
    }

    public function create(int $id, array $data): Service{
        // REGLA DE NEGOCIO, para crear un servicio se debe tener primero un negocio asociado
        $business = $this->businessRepository->findById($id);
        $data['business_id'] = $business->id; // TODO: Preguntar si es correcto asociar el id con el de parámetro en este punto

        return $this->serviceRepository->create($data);
    }

      public function update(int $id, int $serviceId, array $data): Service{
        unset($data['business_id']); // No se debe permitir actualizar el negocio
        $service = $this->serviceRepository->findById($id, $serviceId);

        return $this->serviceRepository->update($service, $data);
    }

    public function delete(int $id, int $serviceId): bool{
        $service = $this->serviceRepository->findById($id, $serviceId);
        $this->serviceRepository->delete($service);
        
        return true;
    }
}