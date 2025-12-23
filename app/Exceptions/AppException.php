<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class AppException extends Exception {
    // Código HTTP por defecto de las excepciones de Dominio -> 404
    protected int $statusCode = Response::HTTP_NOT_FOUND;

    final public function getStatusCode(): int {
        return $this->statusCode;
    }
}
