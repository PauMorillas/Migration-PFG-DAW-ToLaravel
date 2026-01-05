<?php

namespace App\Services;

use App\DTO\Business\BusinessResponse;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;
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
        private BusinessRepositoryInterface $businessRepository,
        private UserService $userService)
    {
    }

    public function findById($id): ?BusinessResponse
    {
        $business = $this->getBusinessModelOrFail($id);

        return BusinessResponse::createFromModel($business);
    }

    public function create(CreateBusinessDTO $dto): ?BusinessResponse
    {
        $this->userService->assertExists($dto->userId);

        $business = $this->businessRepository->create($dto->toArray());

        return BusinessResponse::createFromModel($business);
    }

    public function update(UpdateBusinessDTO $dto): ?BusinessResponse
    {
        $this->assertUserCanModifyBusiness($dto->businessId, $dto->userId);

        $business = $this->getBusinessModelOrFail($dto->businessId);

        $businessUpdt = $this->businessRepository->update($business, $dto->toArray());

        return BusinessResponse::createFromModel($businessUpdt);
    }

    public function delete(int $businessId, int $userId): bool
    {
        $this->assertUserCanModifyBusiness($businessId, $userId);

        $business = $this->getBusinessModelOrFail($userId);

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
    public function assertExists(int $businessId): bool
    {
        $exists = $this->businessRepository->assertExists($businessId);

        if (is_null($exists) || !$exists) {
            throw new BusinessNotFoundException();
        }

        return $exists;
    }

    public function assertUserCanModifyBusiness(int $businessId, int $userId): void
    {
        $user = $this->userService->findById($userId);
        $business = $this->businessRepository->findById($businessId);

        if(!$user) {
            throw new UserNotFoundException();
        }

        if (!$business) {
            throw new BusinessNotFoundException();
        }

        if ($business->user_id !== $userId) {
            throw new UnauthorizedException();
        }
    }
}
