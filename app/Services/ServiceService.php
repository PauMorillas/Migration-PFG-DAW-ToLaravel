<?php
namespace App\Services;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Eloquent\BusinessRepository;
use Illuminate\Database\Eloquent\Collection;

class ServiceService {

    private ServiceRepositoryInterface $serviceRepository;
    private BusinessRepository $businessRepository;

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

    public function create(int $id, array $data){
        // REGLA DE NEGOCIO, para crear un servicio se debe tener primero un negocio asociado
        $business = $this->businessRepository->findById($id);
        $data['business_id'] = $business->id; // TODO: Preguntar si es correcto asociar el id con el de parámetro en este punto

        return $this->serviceRepository->create($data);
    }

   /*  public function update($id, $data): Service{

        return $this->serviceRepository->update($id, $data);
    }

    public function delete($id){
        $this->serviceRepository->delete($id);
        return true;
    } */
}