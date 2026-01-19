<?php

namespace App\DDD\Backoffice\Shared\Domain\Bus;

// La intención de la interfaz es inyectar explícitamente el tipo de bus
interface AsyncCommandBusInterface{
    /**
     * Ejecuta un comando pasándole los datos y
     * llamando al handler correspondiente
     *
     * @param object $command
     * @return mixed
     * */
    public function dispatch(object $command): mixed;
}
