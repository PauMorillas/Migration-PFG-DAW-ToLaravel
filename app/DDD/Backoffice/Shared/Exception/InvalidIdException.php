<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class InvalidIdException extends AppException
{
    public function __construct(?string $message = 'El id no puede ser negativo, ni cero')
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct($message);
    }
}
