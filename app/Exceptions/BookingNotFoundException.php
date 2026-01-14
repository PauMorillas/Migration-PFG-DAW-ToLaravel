<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class BookingNotFoundException extends AppException {

    public function __construct(?string $message = 'Reserva no encontrada') {
        $this->statusCode = Response::HTTP_NOT_FOUND;
        parent::__construct($message);
    }
}
