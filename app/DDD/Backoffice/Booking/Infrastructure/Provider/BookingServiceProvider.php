<?php

namespace App\DDD\Backoffice\Booking\Infrastructure\Provider;

use App\DDD\Backoffice\Booking\Application\Handler\CreatePreBookingHandler;
use App\DDD\Backoffice\Shared\Domain\Bus\CommandBusInterface;
use Inertia\ServiceProvider;

class BookingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            CreatePreBookingHandler::class,
        );

        $this->app->afterResolving(
            CommandBusInterface::class,
            function (CreatePreBookingHandler $handler) {

            });
    }
}
