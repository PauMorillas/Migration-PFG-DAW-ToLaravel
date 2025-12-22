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

    public function findAll(int $id): JsonResponse
    {
        try {
            $servicios = $this->serviceService->findAll($id);
            return $this->ok($servicios);
        } catch (AppException $th) {
            return $this->error($th->getMessage(), $th->getStatusCode());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function findById(int $id, int $serviceId): JsonResponse
    {
        try {
            $service = $this->serviceService->findById($id, $serviceId);
            return $this->ok([$service]);
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
            $service = $this->serviceService->create($dto);

            return $this->created($service);
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
            $service = $this->serviceService->update($dto);

            return $this->ok([$service]);
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

    // TODO: La validacion dista en los mensajes si se meten más parametros de los que espera la validacion
    // Hay que hacerla dinámica pasarle solo los campos a validar no toda la request
    private function validateService(Request $request): void
    {
        $validator = Validator::make(
            $request->all(),
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
            ],
            [
                'title' => 'título',
                'description' => 'descripción',
                'location' => 'ubicación',
                'price' => 'precio',
                'duration' => 'duración',
            ]
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}