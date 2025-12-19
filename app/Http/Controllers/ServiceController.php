<?php

namespace App\Http\Controllers;

use App\DTO\Service\CreateServiceDTO;
use Throwable;
use Illuminate\Http\Request;
use App\Services\ServiceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

// TODO: Revisar los trycatch de errores 500
class ServiceController extends Controller
{
    use ApiResponseTrait;
    private readonly ServiceService $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function findAll(int $id): JsonResponse
    {
        try {
            $servicios = $this->serviceService->findAll($id);
            return $this->ok($servicios);
        } catch (ModelNotFoundException $th) {
            return $this->notFound('Negocio no encontrado');
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    public function findById(int $id, int $serviceId): JsonResponse
    {
        try {
            $service = $this->serviceService->findById($id, $serviceId);
            return $this->ok([$service]);
        } catch (ModelNotFoundException $th) {
            return $this->notFound('Negocio no encontrado');
        } catch (ValidationException $th) {
            return $this->validationError($th->validator->errors()->first());
        } catch (Throwable $th) {
            return $this->internalError($th);
        }

        /* catch (ValidationException | BadRequestException $th) {
            return response()->json(['error' => $th->getMessage()], 400);
        } */

    }

    public function create(int $businessId, Request $request): JsonResponse
    {
        try {
            $this->validateService($request);
            $dto = CreateServiceDTO::createFromArray($request->all(), $businessId);
            $service = $this->serviceService->create($dto);
            
            return $this->created($service);
        } catch (ModelNotFoundException $th) {
            return $this->notFound('Negocio no encontrado');
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
            $service = $this->serviceService->update($id, $serviceId, $request->all());

            return $this->ok([$service]);
        } catch (ModelNotFoundException $th) {
            return $this->notFound('Negocio no encontrado');
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
        } catch (ModelNotFoundException $th) {
            return $this->notFound('Negocio no encontrado');
        } catch (Throwable $th) {
            return $this->internalError($th);
        }
    }

    private function validateService(Request $request)
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