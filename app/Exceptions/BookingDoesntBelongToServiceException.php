<?php

namespace App\Exceptions;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

final class BookingDoesntBelongToServiceException extends AppException
{
    protected int $statusCode = Response::HTTP_UNAUTHORIZED;

    public function __construct()
    {
        parent::__construct('La reserva no pertenece al servicio especificado.');
    }
}
