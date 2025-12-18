<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ServiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

// TODO: Revisar los trycatch de errores 500
class ServiceController extends Controller
{
    private $serviceService;

    public function __construct(ServiceService $serviceService)
    {
        $this->serviceService = $serviceService;
    }

    public function findAll(int $id): JsonResponse {
        try {
            $servicios = $this->serviceService->findAll($id);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }

        return response()->json([$servicios], 200);
    }

    public function findById(int $id, int $serviceId): JsonResponse
    {
        try {
            $service = $this->serviceService->findById($id, $serviceId);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Negocio no encontrado'], 404);
        }

        return response()->json([$service], 200);
    }

    public function create(int $id, Request $request): JsonResponse
    {
        try {
            $this->validateService($request);
            $this->serviceService->create($id, $request->all());

        } catch (ValidationException $ex) {
            return response()->json(['error' => $ex->validator->errors()->first()], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }

        return response()->json(['created' => true], 201);
    }

    public function update(int $id, int $serviceId, Request $request): JsonResponse
    {
        try {
            $this->validateService($request);
            $service = $this->serviceService->update($id, $serviceId, $request->all());

        } catch (ValidationException $ex) {
            return response()->json(['error' => $ex->validator->errors()->first()], 400);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }

        return response()->json($service, 201);
    }

    public function delete(int $id, int $serviceId): JsonResponse
    {
        try {
            $this->serviceService->delete($id, $serviceId);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => $th->getMessage()
            ], 500);
        }

        return response()->json('', 204);
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