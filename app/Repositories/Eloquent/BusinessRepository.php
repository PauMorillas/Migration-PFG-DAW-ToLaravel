<?php
namespace App\Repositories\Eloquent;

use App\Models\Business;
use App\Repositories\Contracts\BusinessRepositoryInterface;

class BusinessRepository implements BusinessRepositoryInterface
{
    public function findById($id): ?Business
    {
        return Business::query()->find($id);
    }

    public function create($data): Business
    {
        return Business::create($data);
    }

    public function update($business, $data): Business
    {
        $business->update($data);
        return $business;
    }

    public function delete(Business $business): void
    {
        $business->delete();
    }
}