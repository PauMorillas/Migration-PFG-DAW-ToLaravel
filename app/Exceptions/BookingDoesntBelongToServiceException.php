<?php

namespace App\Exceptions;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

final class BookingDoesntBelongToServiceException extends AppException
{
    public function __construct(?string $message = 'La reserva no pertenece al servicio especificado.')
    {
        $this->statusCode = Response::HTTP_UNAUTHORIZED;
        parent::__construct($message);
    }
}
