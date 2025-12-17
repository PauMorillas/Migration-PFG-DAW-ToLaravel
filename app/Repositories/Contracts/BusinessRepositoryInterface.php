<?php 
namespace App\Repositories\Contracts;

use App\Models\Business;

interface BusinessRepositoryInterface
{
    public function findById(int $id): Business;
    public function create(array $data): Business;
    public function update(int $id, array $data): Business;
    public function delete(int $id): void;
}