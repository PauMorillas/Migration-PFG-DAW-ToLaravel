<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Provider;

use App\DDD\Backoffice\Shared\Domain\Bus\AsyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Domain\Bus\SyncCommandBusInterface;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Bus;

abstract class AbstractDDDServiceProvider
{
    protected function __construct(private readonly Application $app)
    {
    }

    public static function create(Application $app): self {
        return new static($app);
    }

    public function register(): void {

    }

    protected function getServiceContainer(): Application {
        return $this->app;
    }

    public function getCommandBus(): SyncCommandBusInterface {
        return $this->app->get(SyncCommandBusInterface::class);
    }

    public function getAsyncCommandBus(): AsyncCommandBusInterface {
        return $this->app->get(AsyncCommandBusInterface::class);
    }

    protected function mapQueries(): void {

    }

    protected function mapCommands(): void {

    }

    protected function registerCommands(array $map): void {
        Bus::map($map);
    }

    public function boot(): void
    {
        $this->mapCommands();
        $this->mapQueries();
        // En un futuro:
        // $this->mapEvents();
    }
}
