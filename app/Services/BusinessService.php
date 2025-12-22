<?php
namespace App\Services;

use Bus;
use App\Models\Business;
use App\DTO\Business\CreateBusinessDTO;
use App\DTO\Business\UpdateBusinessDTO;
use App\Exceptions\BusinessNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\BusinessRepositoryInterface;

class BusinessService
{

    public function __construct(
    private readonly BusinessRepositoryInterface $businessRepository)
    {}

    public function findById($id): Business
    {
        $entity = $this->businessRepository->findById($id);

        if (is_null($entity)) {
            throw new BusinessNotFoundException();
        }

        return $entity;
    }

    public function create(CreateBusinessDTO $dto): Business
    {
        // TODO: Buscar si existe una sesión o un usuario con ese negocio asociado
        return $this->businessRepository->create($dto->toArray());
    }

    public function update(UpdateBusinessDTO $dto): Business
    {
        $business = $this->findById($dto->businessId);

        return $this->businessRepository->update($business, $dto->toArray());
    }

    public function delete($id): bool
    {
        $business = $this->findById($id);

        $this->businessRepository->delete($business);
        return true;
    }
}