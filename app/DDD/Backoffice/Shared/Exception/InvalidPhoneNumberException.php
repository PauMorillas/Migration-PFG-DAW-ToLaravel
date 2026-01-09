<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class InvalidPhoneNumberException extends AppException
{
    public function __construct(?string $message = "El numero de teléfono debe tener 9 caracteres")
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct($message);
    }
}
