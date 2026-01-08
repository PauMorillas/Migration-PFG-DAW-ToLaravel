<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class EmailFormatIsNotValidException extends AppException
{
    protected int $statusCode = Response::HTTP_BAD_REQUEST;

    public function __construct() {
        parent::__construct('El formato del email no es válido');
    }
}
