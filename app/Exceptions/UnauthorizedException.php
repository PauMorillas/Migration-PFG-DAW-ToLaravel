<?php

namespace App\Exceptions;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends AppException
{
    public function __construct()
    {
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
        parent::__construct('No puedes modificar datos en este recurso');
    }
}
