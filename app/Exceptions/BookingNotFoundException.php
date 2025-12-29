<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

final class BookingNotFoundException extends AppException {
    protected int $statusCode = Response::HTTP_NOT_FOUND;

    public function __construct() {
        parent::__construct('Reserva no encontrada');
    }
}
