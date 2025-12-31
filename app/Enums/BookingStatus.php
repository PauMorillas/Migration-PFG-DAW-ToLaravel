<?php

namespace App\Enums;

enum BookingStatus: string
{
    case ACTIVA = 'ACTIVA';
    case INACTIVA = 'INACTIVA';
    case CANCELADA = 'CANCELADA';
}
