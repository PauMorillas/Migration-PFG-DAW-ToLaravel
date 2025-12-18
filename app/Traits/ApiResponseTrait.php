<?php 

namespace App\Traits;

use Throwable;
use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected function ok($data = null, ?int $status = 200): JsonResponse
    {
        return response()->json($data, $status);
    }

    protected function error(?string $message = 'Error interno del Servidor', int $status = 500): JsonResponse
    {
        return response()->json(['error' => $message], $status);
    }

    protected function validationError(string $message): JsonResponse
    {
        return $this->error($message, 400);
    }

    protected function notFound(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->error($message, 404);
    }

    protected function created($data = null): JsonResponse
    {
        return $this->ok($data, 201);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Manejo centralizado del 500, Si el APP_DEBUG está activo se muestran los detalles del error
     */
    protected function internalError(Throwable $exception): JsonResponse
    {
        if (config('app.debug')) {
            return response()->json([
                'error'   => $exception->getMessage(),
                'type'    => get_class($exception),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                // OJO: stack trace solo en local
                'trace'   => collect($exception->getTrace())->take(5),
            ], 500);
        }

        return $this->error('Error interno del servidor', 500);
    }

    // Si escala bien hacer el resto:
    // Conflict y Unauthorized (409, 401)
}