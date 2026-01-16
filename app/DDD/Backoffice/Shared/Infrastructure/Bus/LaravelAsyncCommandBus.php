<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Bus;


use App\DDD\Backoffice\Shared\Domain\Bus\AsyncCommandBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

readonly class LaravelAsyncCommandBus implements AsyncCommandBusInterface
{

    public function __construct(
        private Dispatcher $bus
    )
    {
    }

    public function dispatch(object $command): mixed
    {
        return $this->bus->dispatch($command);
    }
}
