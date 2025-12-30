<?php

namespace App\Http\Controllers;

use Throwable;
use Illuminate\Http\Request;
use App\Exceptions\AppException;
use App\Services\ServiceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use App\DTO\Service\CreateServiceDTO;
use App\DTO\Service\UpdateServiceDTO;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// TODO: Revisar los trycatch de errores 500
class ServiceController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private readonly ServiceService $serviceService)
    {
    }

    private const SERVICE_ATTRIBUTES = [
        'title' => 'titulo',
        'description' => 'descripcion',
        'location' => 'ubicacion',
        'price' => 'precio',
        'duration' => 'duracion',
    ];

    public function findAll(int $id): JsonResponse
    {
        try {
            $serviciosResp = $this->serviceService->findAll($id);
            return $this->ok($serviciosResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function findById(int $id, int $serviceId): JsonResponse
    {
        try {
            $serviceResp = $this->serviceService->findById($id, $serviceId);
            return $this->ok([$serviceResp]);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function create(int $businessId, Request $request): JsonResponse
    {
        try {
            $this->validateService($request);
            $dto = CreateServiceDTO::createFromArray($request->all(), $businessId);
            $serviceResp = $this->serviceService->create($dto);

            return $this->created($serviceResp);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function update(int $id, int $serviceId, Request $request): JsonResponse
    {
        try {
            $this->validateService($request);
            $dto = UpdateServiceDTO::createFromArray($request->all(), $id, $serviceId);
            $serviceResp = $this->serviceService->update($dto);

            return $this->ok([$serviceResp]);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function delete(int $id, int $serviceId): JsonResponse
    {
        try {
            $this->serviceService->delete($id, $serviceId);
            return $this->noContent();
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    private function validateService(Request $request): void
    {
        $validator = Validator::make(
            $request->only(array_keys(self::SERVICE_ATTRIBUTES)),
            [
                'title' => 'required|string|max:255',
                'description' => 'required|string|max:255',
                'location' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'duration' => 'required|numeric|min:0',
            ],
            [
                '*.required' => 'El campo :attribute es obligatorio.', // Placeholder que sustituye Laravel con los nombres del 4to parámetro
                '*.string' => 'El campo :attribute debe ser un texto.',
                '*.numeric' => 'El campo :attribute debe ser de tipo numérico',
                '*.max' => 'El campo :attribute debe tener menos de :max caracteres.',
                '*.min' => 'El campo :attribute debe tener al menos :min caracteres.',
            ],
            self::SERVICE_ATTRIBUTES
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
