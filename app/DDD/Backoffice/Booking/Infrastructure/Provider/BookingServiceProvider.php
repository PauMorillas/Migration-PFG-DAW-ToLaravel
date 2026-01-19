<?php

namespace App\DDD\Backoffice\Booking\Infrastructure\Provider;

use App\DDD\Backoffice\Booking\Application\Command\CreatePreBookingCommand;
use App\DDD\Backoffice\Booking\Application\Handler\CreatePreBookingHandler;
use App\DDD\Backoffice\Booking\Domain\Command\SendConfirmationMailCommand;
use App\DDD\Backoffice\Booking\Domain\Handler\SendConfirmationMailHandler;
use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DDD\Backoffice\Booking\Infrastructure\Persistence\EloquentPreBookingRepository;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerServiceInterface;
use App\DDD\Backoffice\Shared\Infrastructure\Provider\AbstractDDDServiceProvider;
use App\DDD\Backoffice\Shared\Infrastructure\Service\Mail\MailerService;
use Exception;
use Illuminate\Support\Facades\Bus;
use ReflectionException;

class BookingServiceProvider extends AbstractDDDServiceProvider
{
    protected function mapCommands(): void
    {
        // TODO: ESTO DEBERÍA SER obteniendo el commandBus y haciendo ::map
        Bus::map([
            CreatePreBookingCommand::class => CreatePreBookingHandler::class,
            SendConfirmationMailCommand::class => SendConfirmationMailHandler::class,
        ]);
    }

    public function register(): void
    {
        $container = $this->getServiceContainer();

        try {
            $container->bind(
                PreBookingRepositoryV2Interface::class,
                EloquentPreBookingRepository::class
            );

            $container->bind(
                MailerServiceInterface::class,
                MailerService::class
            );
        } catch (ReflectionException $e) {
            $file = $e->getFile();
            throw new Exception
            ("Error al resolver las dependencias de interfaces en el archivo: $file");
        }
    }
}
