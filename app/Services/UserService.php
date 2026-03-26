<?php

namespace App\Services;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\DTO\User\UserLoginRequest;
use App\DTO\User\UserResponseDTO;
use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserNotFoundException;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function findById(int $userId): ?UserResponseDTO
    {
        $user = $this->userRepository->findById($userId);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return UserResponseDTO::createFromModel($user);
    }

    /*public function login(UserLoginRequest $dto): ?UserResponseDTO
    {
        $user = $this->userRepository->findByEmail(
            $dto->getEmail());

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        if (!Hash::check($dto->getPassword(), $user->password)) {
            throw new InvalidCredentialsException();
        }

        return UserResponseDTO::createFromModel($user);
    }*/
    public function login(UserLoginRequest $dto): array
    {
        $credentials = [
            'email' => $dto->getEmail(),
            'password' => $dto->getPassword(),
        ];

        if (!Auth::attempt($credentials)) {
            throw new UserNotFoundException();
        }

        request()->session()->regenerate();

        $user = Auth::user();

        return [
            'user' => UserResponseDTO::createFromModel($user),
        ];
    }

    public function register(CreateUserDTO $dto): ?UserResponseDTO
    {
        $data = $dto->toArray() + ['password' => Hash::make($dto->getPassword())];
        $user = $this->userRepository->create($data);

        return UserResponseDTO::createFromModel($user);
    }

    public function delete(int $userId, int $authUserId): void
    {
        $user = $this->getUserModelOrFail($userId);
        $this->assertIsOwner($userId, $authUserId);

        $this->userRepository->delete($user);
    }

    public function update(UpdateUserDTO $dto, int $authUserId): ?UserResponseDTO
    {
        $data = $dto->toArray() + ['password' => Hash::make($dto->getPassword())];

        $user = $this->getUserModelOrFail($data['userId']);
        $this->assertIsOwner($dto->userId, $authUserId);

        $this->userRepository->update($user, $data);

        return UserResponseDTO::createFromModel($user);
    }

    // Método privado para obtener la entidad de la bd
    private function getUserModelOrFail(int $userId): User
    {
        $user = $this->userRepository->findById($userId);

        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        return $user;
    }

    public function assertExists(int $userId): bool
    {
        $exists = $this->userRepository->assertExists($userId);

        if (is_null($exists) || !$exists) {
            throw new UserNotFoundException();
        }

        return true;
    }

    public function assertIsOwner(int $userId, int $authUserId): void
    {
        if ($userId !== $authUserId) {
            throw new UnauthorizedException();
        }
    }
}
