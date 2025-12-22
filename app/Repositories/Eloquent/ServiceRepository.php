<?php
namespace App\Repositories\Eloquent;

use App\Models\Service;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ServiceRepository implements ServiceRepositoryInterface
{
    public function findAll(int $id): Collection
    {
        return Service::query()
        ->where('business_id', $id)
        ->get();
    }
    
    public function findById(int $serviceId): ?Service
    {
        return Service::query()->find($serviceId);
    }

    public function create(array $data): Service
    {
        return Service::create($data);
    }

    public function update(Service $service, array $data): Service
    {
        $service->update($data);

        return $service;
    }

    public function delete(Service $service): void
    {
        $service->delete();
    }
}
