<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Bus;

use http\Exception\RuntimeException;

final readonly class SimpleCommandBus implements CommandBusInterface
{
    /**
     * @param array $handlers <string, callable> $handlers
     */
    public function __construct(
        private array $handlers
    )
    {}

    public function dispatch(object $command): mixed
    {
        $commandClass = $command::class;

        if(!isset($this->handlers[$commandClass])) {
            throw new RuntimeException("No handler for command $commandClass");
        }

        // Llama al handler correspondiente pasando el command
        return ($this->handlers[$commandClass])($command);
    }
}
