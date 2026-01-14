<?php

namespace App\DDD\Backoffice\Shared\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class InvalidAppDateException extends AppException
{
    public function __construct(?string $message = 'EL formato de la fecha introducida no es válido.')
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct($message);
    }
}
