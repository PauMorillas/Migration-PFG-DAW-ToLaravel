<?php

namespace App\DDD\Backoffice\Shared\Domain\Bus;

interface CommandBusInterface
{
    /**
     * Ejecuta un comando pasándole los datos y
     * llamando al handler correspondiente
     *
     * @param object $command
     * @return mixed
     * */
    public function dispatch(object $command): mixed;
    public function map(array $map): void;
}
