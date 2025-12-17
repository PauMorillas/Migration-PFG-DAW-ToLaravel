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

    public function findById($id)
    {
        return $this->repository->findById($id);
    }

    public function create($data)
    {
        return $this->repository->create($data);
    }

    public function update($id, $data)
    {
        return $this->repository->update($id,$data);
    }

    public function delete($id)
    {
        $this->repository->delete($id);
        return true;
    }
}