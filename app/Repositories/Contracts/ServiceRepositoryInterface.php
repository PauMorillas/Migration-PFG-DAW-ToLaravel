<?php

namespace App\Repositories\Contracts;

use App\Models\Service;

interface ServiceRepositoryInterface
{
    public function findById(int $id): Service;
    public function create(array $data): Service;
    public function update(int $id, array $data): Service;
    public function delete(int $id): void;
}
