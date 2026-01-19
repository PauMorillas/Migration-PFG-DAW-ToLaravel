<?php

namespace App\Providers;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Command\DeletePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Command\FindByIdPreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\CreatePreBookingHandler;
use App\DDD\Backoffice\Booking\Application\Handler\DeletePreBookingHandler;
use App\DDD\Backoffice\Booking\Application\Handler\FindByIdPreBookingHandler;
use App\DDD\Backoffice\Shared\Domain\Bus\AsyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Domain\Bus\CommandBusInterface;
use App\DDD\Backoffice\Shared\Domain\Bus\SyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\SimpleCommandBus;
use Illuminate\Support\ServiceProvider;

class CommandBusServiceProvider extends ServiceProvider
{
    // En el register se hacen los bindings
    public function register(): void
    {
        $this->app->singleton(CommandBusInterface::class, function ($app) {
            return new SimpleCommandBus([
                CreatePreBookingCommand::class =>
                    $app->make(CreatePreBookingHandler::class),

                FindByIdPreBookingCommand::class =>
                $app->make(FindByIdPreBookingHandler::class),

                DeletePreBookingCommand::class =>
                $app->make(DeletePreBookingHandler::class),
            ]);
        });

    }

    public function boot() {

    }
}
