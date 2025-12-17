<?php
namespace App\Repositories\Eloquent;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function findById(int $id): Service
    {
        return Service::findOrFail($id);
    }

    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(int $id, array $data): Service
    {
        $service = Service::findOrFail($id);
        $service->update($data);

        return $service;
    }

    public function delete(int $id): void
    {
        Service::findOrFail($id)->delete();
    }
}
