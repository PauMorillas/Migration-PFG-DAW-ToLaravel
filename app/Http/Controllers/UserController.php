<?php

namespace App\Http\Controllers;

use App\DTO\User\CreateUserDTO;
use App\DTO\User\UpdateUserDTO;
use App\DTO\User\UserLoginRequest;
use App\DTO\User\UserResponseDTO;
use App\Exceptions\AppException;
use App\Traits\ApiResponseTrait;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
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
        'telephone' => 'telefono',
        'password' => 'contraseña',
        'role' => 'rol',
    ];

    public function __construct(private readonly UserService $userService)
    {
    }

    public function findById(int $userId): JsonResponse
    {
        try {
            $userResp = $this->userService->findById($userId);
            return $this->ok($userResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $this->validateUser($request,
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
            $this->validateUser($request,
                [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'telephone' => 'nullable|numeric|digits:9|unique:users,telephone',
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

    public function me(Request $request): JsonResponse
    {
        $user = UserResponseDTO::createFromModel($request->user());
        return $this->ok($user); // Obtiene el usuario que hace la request
    }

    public function update(int $userId, Request $request): JsonResponse
    {
        try {
            $this->validateUser($request,
                [
                    'name' => 'required|string|max:255',
                    'email' => [
                        'required',
                        'email',
                        Rule::unique('users', 'email')->ignore($userId),
                    ],
                    'telephone' => [
                        'nullable',
                        'numeric',
                        'digits:9',
                        Rule::unique('users', 'telephone')->ignore($userId),
                    ],
                    'password' => [
                        'required',
                        'string',
                        Password::min(8)
                            ->letters()
                            ->numbers(),
                    ],
                ], self::USER_ATTRIBUTES);

            // TODO: no se puede actualizar el rol, lo forzamos
            $data = $request->only(['name','email','telephone','password']);
            $data['role'] = 'CLIENTE';

            $dto = UpdateUserDTO::createFromArray($data, $userId);

            $authUserId = $request->user()->id;
            $userResp = $this->userService->update($dto, $authUserId);

            return $this->ok($userResp);
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function delete(int $userId, Request $request): JsonResponse
    {
        try {
            $authUserId = $request->user()->id;
            $this->userService->delete($userId, $authUserId);

            return $this->noContent();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    private function validateUser(
        Request $request,
        array   $rules,
        array   $attributes): void
    {
        $validator = Validator::make(
            $request->only(array_keys($rules)), // Sólo validará lo que se le especifique
            $rules,
            [
                '*.required' => 'El campo :attribute es obligatorio.',
                '*.string' => 'El campo :attribute debe ser un texto.',
                '*.email' => 'El campo :attribute debe tener un formato valido.',
                '*.unique' => 'El :attribute ya esta registrado.',
                'password.letters' => 'La :attribute debe contener al menos una letra.',
                'password.numbers' => 'La :attribute debe contener al menos un número.',
                'password.min' => 'La :attribute debe tener al menos :min caracteres.',
                'telephone.unique' => 'El teléfono ya está en uso por otro usuario.',
                'telephone.digits' => 'El teléfono debe tener el formato español, es decir, :digits digitos'
                ],
            $attributes
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
