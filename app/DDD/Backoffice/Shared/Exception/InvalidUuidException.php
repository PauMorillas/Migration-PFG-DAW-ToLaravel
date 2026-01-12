<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class InvalidUuidException extends AppException
{
    public function __construct() {
        // Para asignar el status code se hace asi, no por constructor
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct('El Uuid no tiene un formato válido');
    }
}
