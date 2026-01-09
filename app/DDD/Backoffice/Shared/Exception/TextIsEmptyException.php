<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class TextIsEmptyException extends AppException
{
    public function __construct()
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct('El campo no puede estar vacío, debe contener texto');
    }
}
