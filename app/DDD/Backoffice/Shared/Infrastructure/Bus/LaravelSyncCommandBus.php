<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Bus;

use App\DDD\Backoffice\Shared\Domain\Bus\SyncCommandBusInterface;
use Illuminate\Contracts\Bus\Dispatcher;

readonly class LaravelSyncCommandBus implements SyncCommandBusInterface
{
    public function __construct(
        private  Dispatcher $bus,
    )
    {
    }

    public function dispatch(object $command): mixed
    {
        return $this->bus->dispatchSync($command);
    }
}
