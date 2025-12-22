<?php

namespace App\Exceptions;

use App\Exceptions\AppException;
final class UserNotFoundException extends AppException {
    public function __construct() {
        parent::__construct('Usuario no encontrado');
    }
}
