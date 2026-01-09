<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class TextIsPassingMaxLenghtException extends AppException
{
    public function __construct(?int $characterCount = 255)
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct("El texto no puede pasar de los {$characterCount} caracteres");
    }
}
