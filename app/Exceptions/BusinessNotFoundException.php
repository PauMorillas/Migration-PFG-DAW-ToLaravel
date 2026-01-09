<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class BusinessNotFoundException extends AppException
{
    public function __construct()
    {
        $this->statusCode = Response::HTTP_NOT_FOUND;
        parent::__construct('Negocio no encontrado');
    }
}
