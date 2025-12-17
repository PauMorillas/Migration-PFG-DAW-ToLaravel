<?php
namespace App\Services;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class ServiceService {

    private ServiceRepositoryInterface $repository;

    public function __construct(ServiceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function findById(int $id) {
        return $this->repository->findById($id);
    }

    public function create($data){
        return $this->repository->create($data);
    }

    public function update($id, $data): Service{
        return $this->repository->update($id, $data);
    }

    public function delete($id){
        $this->repository->delete($id);
        return true;
    }
}