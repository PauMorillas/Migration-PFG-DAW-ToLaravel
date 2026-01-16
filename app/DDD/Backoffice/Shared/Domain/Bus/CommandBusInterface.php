<?php

namespace App\DDD\Backoffice\Shared\Domain\Bus;

interface CommandBusInterface
{
    public function dispatch(object $command): mixed;
}
