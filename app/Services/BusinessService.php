<?php
namespace App\Services;

use App\DTO\Business\CreateBusinessDTO;
use App\DTO\Business\UpdateBusinessDTO;
use Bus;
use App\Models\Business;
use App\Repositories\Contracts\BusinessRepositoryInterface;

class BusinessService
{
    private readonly BusinessRepositoryInterface $repository;

    public function __construct(BusinessRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById($id): Business
    {
        return $this->repository->findById($id);
    }

    public function create(CreateBusinessDTO $dto): Business
    {
        // TODO: Buscar si existe una sesión o un usuario con ese negocio asociado
        return $this->repository->create($dto->toArray());
    }

    public function update(UpdateBusinessDTO $dto): Business
    {
        $business = $this->repository->findById($dto->businessId);

        return $this->repository->update($business,$dto->toArray());
    }

    public function delete($id): bool
    {
        $business = $this->repository->findById($id);

        $this->repository->delete($business);
        return true;
    }
}