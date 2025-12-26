<?php

namespace App\Services;

use App\DTO\Business\BusinessResponse;
use Bus;
use App\Models\Business;
use App\DTO\Business\CreateBusinessDTO;
use App\DTO\Business\UpdateBusinessDTO;
use App\Exceptions\BusinessNotFoundException;
use App\Repositories\Contracts\BusinessRepositoryInterface;


// TODO: SE DEBE VALIDAR QUE EL USUARIO ASOCIADO EXISTE ANTES DE NINGUNA CRUD
// y que le pertenecen los negocios y servicios que quiere editar
readonly class BusinessService
{

    public function __construct(
        private readonly BusinessRepositoryInterface $businessRepository)
    {
    }

    public function findById($id): ?BusinessResponse
    {
        $business = $this->getBusinessModelOrFail($id);

        return BusinessResponse::createFromModel($business);
    }

    public function create(CreateBusinessDTO $dto): ?BusinessResponse
    {
        // TODO: Buscar si existe una sesión o un usuario con ese negocio asociado
        $business = $this->businessRepository->create($dto->toArray());

        return BusinessResponse::createFromModel($business);
    }

    public function update(UpdateBusinessDTO $dto): ?BusinessResponse
    {
        $business = $this->getBusinessModelOrFail($dto->businessId);

        $businessUpdt = $this->businessRepository->update($business, $dto->toArray());

        return BusinessResponse::createFromModel($businessUpdt);
    }

    public function delete($id): bool
    {
        $business = $this->getBusinessModelOrFail($id);

        $this->businessRepository->delete($business);
        return true;
    }

    private function getBusinessModelOrFail(int $id): ?Business
    {
        $entity = $this->businessRepository->findById($id);

        if (is_null($entity)) {
            throw new BusinessNotFoundException();
        }

        return $entity;
    }

    /** Lo usan las funciones del servicio de servicios
     * para validar existencia en vez de devolver entidad y checkear
     * */
    public function assertExists(int $id): bool
    {
        $exists = $this->businessRepository->assertExists($id);

        if (is_null($exists) || !$exists) {
            throw new BusinessNotFoundException();
        }

        return $exists;
    }

}
