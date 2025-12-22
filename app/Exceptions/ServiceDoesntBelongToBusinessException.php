<?php 

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class ServiceDoesntBelongToBusinessException extends AppException {
    public function __construct() {
        // Para asignar el status code se hace asi, no por constructor 
        $this->statusCode = Response::HTTP_FORBIDDEN;
        parent::__construct('El servicio no pertenece al negocio especificado');
    }
}