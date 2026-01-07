<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class PreBookingExpiredException extends AppException
{
    protected int $statusCode = Response::HTTP_GONE; // El recurso existía pero ha dejado de estar disponible

    public function __construct()
    {
        parent::__construct('El tiempo para confirmar la PreReserva ha expirado. Por favor, reserve de nuevo.');
    }
}
