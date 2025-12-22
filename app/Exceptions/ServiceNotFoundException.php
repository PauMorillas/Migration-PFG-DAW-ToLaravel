<?php

namespace App\Exceptions;

use App\Exceptions\AppException;

final class ServiceNotFoundException extends AppException {
    public function __construct() {
        parent::__construct('Servicio no encontrado');
    }
}