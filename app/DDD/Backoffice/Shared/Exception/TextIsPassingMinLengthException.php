<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class TextIsPassingMinLengthException extends AppException
{

    public function __construct($minLength)
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct("El texto debe pasar los {$minLength} caracteres.");
    }
}
