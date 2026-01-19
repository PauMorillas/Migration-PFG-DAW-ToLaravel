<?php

namespace App\DDD\Backoffice\Shared\Infrastructure\Provider;

// TODO IMPORTAR LA CLASE Application, no se cual de todas es
use App\DDD\Backoffice\Shared\Infrastructure\Bus\CommandBusInterface;
use Illuminate\Foundation\Application;

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

    protected function getCommandBus(): CommandBusInterface {
        return $this->app->get(CommandBusInterface::class);
    }

    protected function mapQueries(): void {

    }

    protected function mapCommands(): void {

    }

    public function boot(): void
    {
        $this->mapCommands();
        $this->mapQueries();
        // En un futuro:
        // $this->mapEvents();
    }
}
