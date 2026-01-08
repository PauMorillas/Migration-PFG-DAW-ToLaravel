<?php

namespace App\DDD\Backoffice\Booking\Domain\Exception;

use App\Exceptions\AppException;
use Symfony\Component\HttpFoundation\Response;

class BookingDateIsOnPastException extends AppException
{
    protected int $statusCode = Response::HTTP_BAD_REQUEST;
    public function __construct()
    {
        parent::__construct('La reserva contiene una fecha en el pasado');
    }
}
