<?php

namespace App\Services;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\DTO\User\UserLoginRequest;
use App\DTO\User\UserResponse;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    // TODO: ESTO HAY QUE REVISARLO PARA QUE NO DEVUELVA EL RESPONSE, ESTAMOS EN UN PORBLEMA DE ARQUITECTURA ALO
    public function findById(int $userId): ?UserResponse
    {
        $user = $this->userRepository->findById($userId);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return UserResponse::createFromModel($user);
    }

    public function login(UserLoginRequest $dto): ?UserResponse
    {
        $user = $this->userRepository->findByEmail(
            $dto->getEmail());

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        if (!Hash::check($dto->getPassword(), $user->password)) {
            throw new InvalidCredentialsException();
        }

        return UserResponse::createFromModel($user);
    }

    public function register(CreateUserDTO $dto): ?UserResponse
    {
        $data = $dto->toArray() + ['password' => Hash::make($dto->getPassword())];
        $user = $this->userRepository->create($data);

        return UserResponse::createFromModel($user);
    }

    public function delete(int $userId): void
    {
        $user = $this->findById($userId);

        $this->userRepository->delete($user);
    }

    public function update(UpdateUserDTO $dto): ?UserResponse
    {
        $data = $dto->toArray() + ['password' => Hash::make($dto->getPassword())];
        $user = $this->findById($data['userId']);

        $this->userRepository->update($user, $data);

        return UserResponse::createFromModel($user);
    }

}
