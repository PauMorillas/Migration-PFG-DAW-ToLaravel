<?php

namespace App\Http\Controllers;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\DTO\User\UserLoginRequest;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Throwable;

class UserController extends Controller
{
    use ApiResponseTrait;

    private const USER_ATTRIBUTES = [
        'name' => 'nombre',
        'email' => 'correo electronico',
        'password' => 'contraseña',
        'role' => 'rol',
    ];

    public function __construct(private readonly UserService $userService)
    {
    }

    public function findById(int $userId): JsonResponse
    {
        try {
            $userResponse = $this->userService->findById($userId);
            return $this->ok($userResponse);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $this->validate($request,
                [
                    'email' => 'required|email',
                    'password' => [
                        'required',
                        'string',
                        Password::min(8)
                            ->letters()
                            ->numbers(),
                    ],
                ], self::USER_ATTRIBUTES);
            $dto = UserLoginRequest::createFromArray($request->all());
            $userResponse = $this->userService->login($dto);

            return $this->ok($userResponse);
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function register(Request $request): JsonResponse
    {
        try {
            $this->validate($request,
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => [
                        'required',
                        'string',
                        Password::min(8)
                            ->letters()
                            ->numbers(),
                    ]
                    ,
                ], self::USER_ATTRIBUTES);

            $dto = CreateUserDTO::createFromArray($request->all());
            $userResp = $this->userService->register($dto);

            return $this->ok($userResp);
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function update(int $userId, Request $request): JsonResponse
    {
        try {
            $this->validate($request,
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => [
                        'required',
                        'string',
                        Password::min(8)
                            ->letters()
                            ->numbers(),
                    ],
                ], self::USER_ATTRIBUTES);
            $dto = UpdateUserDTO::createFromArray($request->all(), $userId);
            $userResp = $this->userService->update($dto);

            return $this->ok($userResp);
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function delete(int $userId): JsonResponse
    {
        try {
            $this->userService->delete($userId);

            return $this->noContent();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    private function validate(
        Request $request,
        array   $rules,
        array   $attributes): void
    {
        $validator = Validator::make(
            $request->only(array_keys($rules)), // Sólo validará lo que se le pase
            $rules,
            [
                '*.required' => 'El campo :attribute es obligatorio.',
                '*.string' => 'El campo :attribute debe ser un texto.',
                '*.email' => 'El campo :attribute debe tener un formato valido.',
                '*.unique' => 'El :attribute ya esta registrado.',
                'password.letters' => 'La :attribute debe contener al menos una letra.',
                'password.numbers' => 'La :attribute debe contener al menos un número.',
                'password.min' => 'La :attribute debe tener al menos :min caracteres.',
            ],
            $attributes
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
