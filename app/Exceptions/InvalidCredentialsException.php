<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends AppException
{
    public function __construct(?string $message = 'Las credenciales introducidas son incorrectas')
    {
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
        parent::__construct($message);
    }
}
