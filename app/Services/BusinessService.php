<?php
namespace App\Services;

use Bus;
use App\Models\Business;
use App\Repositories\Contracts\BusinessRepositoryInterface;

class BusinessService
{
    private BusinessRepositoryInterface $repository;

    public function __construct(BusinessRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById($id): Business
    {
        return $this->repository->findById($id);
    }

    public function create($data): Business
    {
        // TODO: Buscar si existe una sesión o un usuario con ese negocio asociado
        return $this->repository->create($data);
    }

    public function update($id, $data): Business
    {
        $business = $this->repository->findById($id);
        return $this->repository->update($business,$data);
    }

    public function delete($id): bool
    {
        $business = $this->repository->findById($id);

        $this->repository->delete($business);
        return true;
    }
}