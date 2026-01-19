<?php

namespace App\Providers;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\CreatePreBookingHandler;
use App\DDD\Backoffice\Booking\Domain\Handler\SendConfirmationMailHandler;
use App\DDD\Backoffice\Shared\Domain\Bus\AsyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Domain\Bus\SyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\LaravelAsyncCommandBus;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\LaravelSyncCommandBus;
use Illuminate\Support\Facades\Bus;
use Inertia\ServiceProvider;
use App;

final class DDDServiceProvider extends ServiceProvider
{

    // TODO: REALMENTE EL CONSTRUCTOR ES NECESARIO AQUÍ??
    // no me funcionaba con:
    /*
     * protected function __construct() {
     *      parent::__construct($this->app); // Pero esto era null
     * }
     * */

    public function register(): void {
        // Buses
        $this->app->singleton(
            SyncCommandBusInterface::class,
            LaravelSyncCommandBus::class
        );

        $this->app->singleton(
            AsyncCommandBusInterface::class,
            LaravelAsyncCommandBus::class
        );

        // Bind de Handlers (a mano, sin magia)
        $this->app->bind(
            SendConfirmationMailHandler::class
        );
    }

    // Esto luego deberá mapear
    public function boot(): void
    {
        Bus::map([
            CreatePreBookingCommand::class => CreatePreBookingHandler::class,
        ]);
    }
}
