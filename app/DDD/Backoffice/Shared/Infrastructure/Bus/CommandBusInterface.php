<?php


namespace App\DDD\Backoffice\Shared\Infrastructure\Bus;

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
}
