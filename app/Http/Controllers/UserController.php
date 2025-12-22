<?php

namespace App\Http\Controllers;

use App\DTO\User\CreateUserDTO;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;


class UserController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly UserService $userService)
    {

    }
    public function findById(int $userId): JsonResponse
    {
        try {
            $user = $this->userService->findById($userId);
            return $this->ok($user);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function login($data): JsonResponse {
        try {
            $dto = CreateUserDTO::createFromArray($data);
            $this->userService->login($dto);
            // TODO: SEGUIR CON EL SERVICE Y HACER UN loginRequestDTO
            return $this->ok();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    // TODO: Por implementar
    public function register($data): JsonResponse {
        try {
            $dto = CreateUserDTO::createFromArray($data);
            $this->userService->register($dto);

            return $this->ok();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }
}
