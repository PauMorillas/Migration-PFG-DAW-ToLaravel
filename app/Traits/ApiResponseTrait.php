<?php 

namespace App\Traits;

use Throwable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    protected function ok($data = null, ?int $status = Response::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }
        protected function error(?string $message = 'Error interno del Servidor', ?int $status = Response::HTTP_INTERNAL_SERVER_ERROR): JsonResponse
    {
        return response()->json(['error' => $message], $status);
    }

    protected function validationError(string $message): JsonResponse
    {
        return $this->error($message, Response::HTTP_BAD_REQUEST);
    }

    protected function notFound(string $message = 'Recurso no encontrado'): JsonResponse
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    protected function created($data = null): JsonResponse
    {
        return $this->ok($data, Response::HTTP_CREATED);
    }

    protected function noContent(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
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
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->error('Error interno del servidor', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Si escala bien hacer el resto:
    // Conflict y Unauthorized (409, 401)
}