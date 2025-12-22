<?php

namespace App\Services;

use App\Exceptions\UserNotFoundException;
use App\Repositories\Contracts\UserRepositoryInterface;

readonly class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function findById(int $id) {
        $user = $this->userRepository->findById($id);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    // TODO: Resto de CRUD
}
