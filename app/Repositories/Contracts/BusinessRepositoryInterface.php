<?php
namespace App\Repositories\Contracts;

use App\Models\Business;

interface BusinessRepositoryInterface
{
    public function findById(int $id): ?Business;
    public function create(array $data): Business;
    public function update(Business $business, $data): Business;
    public function delete(Business $business): void;
    public function assertExists(int $id): bool;
}
