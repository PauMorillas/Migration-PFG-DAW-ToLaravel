<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class PreBookingExpiredException extends AppException
{

    public function __construct(?string $message = 'El tiempo para confirmar la PreReserva ha expirado. Por favor, reserve de nuevo.')
    {
        $this->statusCode = Response::HTTP_GONE; // El recurso existía pero ha dejado de estar disponible
        parent::__construct($message);
    }
}
