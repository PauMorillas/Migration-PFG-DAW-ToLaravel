<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Bus;

use App\DDD\Backoffice\Shared\Domain\Bus\CommandBusInterface;
use Exception;

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
            throw new Exception("No handler for command $commandClass");
        }

        // Llama al handler correspondiente pasando el command
        return ($this->handlers[$commandClass])($command);
    }

    public function map(array $map): void
    {
        // TODO: Implement map() method.
    }
}
