<?php

namespace App\Exceptions;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends AppException
{

    protected int $statusCode = Response::HTTP_UNAUTHORIZED;

    public function __construct()
    {
        parent::__construct('No puedes modificar datos en este negocio');
    }
}
