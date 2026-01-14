<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class PasswordIsWeakException extends AppException
{
    protected int $statusCode = Response::HTTP_BAD_REQUEST;

    public function __construct()
    {
        parent::__construct('La contraseña es muy débil debe tener al menos 8 caracteres, un número y una letra');
    }
}
