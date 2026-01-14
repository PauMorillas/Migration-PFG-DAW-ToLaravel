<?php

namespace App\DDD\Backoffice\Booking\Domain\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class BookingDateIsOnPastException extends AppException
{
    public function __construct()
    {
        $this->statusCode = Response::HTTP_BAD_REQUEST;
        parent::__construct('La reserva contiene una fecha en el pasado');
    }
}
