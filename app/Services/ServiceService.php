<?php

namespace App\Services;

use App\DTO\Service\ServiceResponse;
use App\DTO\Service\UpdateServiceDTO;
use App\Exceptions\ServiceDoesntBelongToBusinessException;
use App\Models\Service;
use App\DTO\Service\CreateServiceDTO;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Eloquent\BusinessRepository;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Exceptions\ServiceNotFoundException;
use PhpParser\Error;

readonly class ServiceService
{
    public function __construct(
        private ServiceRepositoryInterface $serviceRepository,
        private BusinessService            $businessService,
    )
    {
    }

    /**
     * @return ServiceResponse[]
     */
    public function findAll(int $businessId): ?array
    {
        $this->businessService->assertExists($businessId); // Primero se valida la existencia

        $services = $this->serviceRepository->findAll($businessId);

        return $services->map(function (Service $service) {
            return ServiceResponse::createFromModel($service);
        })->toArray();
    }

    public
    function findById(int $businessId, $serviceId): ?ServiceResponse
    {
        // TODO: ¿Es correcto? llamar al service en vez de al repo y
        // volver a validar la existencia, lanzar excepcion, etc. Yo diría que si
        $this->businessService->assertExists($businessId);

        $service = $this->getServiceModelOrFail($serviceId);

        $this->assertServiceBelongsToBusiness($service, $businessId);

        return ServiceResponse::createFromModel($service);
    }

    public function create(CreateServiceDTO $dto, int $authUserId): ?ServiceResponse
    {
        // REGLA DE NEGOCIO, para crear un servicio se debe tener primero un negocio asociado
        $this->businessService->assertExists($dto->businessId);
        $this->businessService->assertUserCanModifyBusiness($dto->businessId, $authUserId);

        $service = $this->serviceRepository->create($dto->toArray());

        return ServiceResponse::createFromModel($service);
    }

    public
    function update(UpdateServiceDTO $dto, int $authUserId): ?ServiceResponse
    {
        $this->businessService->assertExists($dto->businessId);

        $service = $this->getServiceModelOrFail($dto->serviceId);

        // Si el negocio del dto es diferente al del service, se manda una excepción
        $this->assertServiceBelongsToBusiness($service, $dto->businessId);

        $this->businessService->assertUserCanModifyBusiness($dto->businessId, $authUserId);

        $service = $this->serviceRepository->update($service, $dto->toArray());

        return ServiceResponse::createFromModel($service);
    }

    public
    function delete(int $businessId, int $serviceId, int $authUserId): bool
    {
        $this->businessService->assertExists($businessId);

        $service = $this->getServiceModelOrFail($serviceId);
        // Si el negocio del dto es diferente al del service, se manda una excepción
        $this->assertServiceBelongsToBusiness($service, $businessId);

        $this->businessService->assertUserCanModifyBusiness($businessId, $authUserId);

        $this->serviceRepository->delete($service);

        return true;
    }

    private
    function getServiceModelOrFail(int $id): ?Service
    {
        $service = $this->serviceRepository->findById($id);

        if (is_null($service)) {
            throw new ServiceNotFoundException();
        }

        return $service;
    }

    private
    function assertServiceBelongsToBusiness(Service $service, int $businessId): void
    {
        if ($service->business_id !== $businessId) {
            throw new ServiceDoesntBelongToBusinessException();
        }
    }

    public function assertExists(int $serviceId): bool {
        $exists = $this->serviceRepository->assertExists($serviceId);

        if(is_null($exists) || !$exists) {
            throw new ServiceNotFoundException();
        }

        return $exists;
    }
}
