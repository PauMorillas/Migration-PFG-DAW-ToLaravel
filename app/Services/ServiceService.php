<?php

namespace App\Services;

use App\DTO\Service\UpdateServiceDTO;
use App\Exceptions\ServiceDoesntBelongToBusinessException;
use App\Models\Service;
use App\DTO\Service\CreateServiceDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Eloquent\BusinessRepository;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Exceptions\ServiceNotFoundException;
use PhpParser\Error;

class ServiceService
{
    public function __construct(
        private readonly ServiceRepositoryInterface $serviceRepository,
        private readonly BusinessRepository $businessRepository,
        private readonly BusinessService $businessService,
    ) {}

    public function findAll(int $businessId): Collection
    {
        $this->businessService->findById($businessId); // Primero se valida la existencia

        return $this->serviceRepository->findAll($businessId);
    }

    public function findById(int $businessId, $serviceId): Service
    {
        // TODO: ¿Es correcto? llamar al service en vez de al repo y
        // volver a validar la existencia, lanzar excepcion, etc.
        $this->businessService->findById($businessId);

        $service = $this->serviceRepository->findById($serviceId);

        if (is_null($service)) {
            throw new ServiceNotFoundException();
        }

        return $service;
    }

    public function create(CreateServiceDTO $dto): Service
    {
        // REGLA DE NEGOCIO, para crear un servicio se debe tener primero un negocio asociado
        $this->businessService->findById($dto->businessId);

        return $this->serviceRepository->create($dto->toArray());
    }

    public function update(UpdateServiceDTO $dto): Service
    {
        $service = $this->findById($dto->businessId, $dto->serviceId);

        // Si el negocio del dto es diferente al del service, se manda una excepción
        if($dto->businessId != $service->business_id) {
            throw new ServiceDoesntBelongToBusinessException();
        }

        return $this->serviceRepository->update($service, $dto->toArray());
    }

    public function delete(int $businessId, int $serviceId): bool
    {
        $service = $this->findById($businessId, $serviceId);
        // Si el negocio del dto es diferente al del service, se manda una excepción
        if($businessId != $service->business_id) {
            throw new ServiceDoesntBelongToBusinessException();
        }
        
        $this->serviceRepository->delete($service);

        return true;
    }
}