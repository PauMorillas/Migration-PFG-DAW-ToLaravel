<?php

namespace App\Providers;

use App\DDD\Backoffice\Shared\Domain\Bus\AsyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Domain\Bus\SyncCommandBusInterface;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\LaravelAsyncCommandBus;
use App\DDD\Backoffice\Shared\Infrastructure\Bus\LaravelSyncCommandBus;
use App\DDD\Backoffice\Shared\Infrastructure\Provider\AbstractDDDServiceProvider;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\File;
use Inertia\ServiceProvider;
use App;

final class DDDServiceProvider extends ServiceProvider
{
    /** @var AbstractDDDServiceProvider */
    private array $providers = [];

    public function register(): void {
        /* == Infraestructura común == */
        // Buses
        $this->app->singleton(
            SyncCommandBusInterface::class,
            LaravelSyncCommandBus::class
        );

        $this->app->singleton(
            AsyncCommandBusInterface::class,
            LaravelAsyncCommandBus::class
        );

        // Descubre los Service Providers dentro de DDD y los registra
        $this->providers = $this->resolveDDDProviders();

        foreach ($this->providers as $provider) {
            $provider->register();
        }
    }

    // Esto luego deberá mapear
    public function boot(): void
    {
        foreach ($this->providers as $provider) {
            $provider->boot();
        }



       /* Bus::map([
            CreatePreBookingCommand::class => CreatePreBookingHandler::class,
            SendConfirmationMailCommand::class => SendConfirmationMailHandler::class,
        ]); */
    }

    /* Descubre todos los AbstractDDDServiceProviders dentro de DDD */
    private function resolveDDDProviders(): array
    {
        $providers = [];

        $basePath = app_path('DDD/Backoffice');

        foreach (File::allFiles($basePath) as $file) {
            if (! str_ends_with($file->getFilename(), 'ServiceProvider.php')) {
                continue;
            }

            $class = $this->getClassFromFile($file->getPathname());

            if (is_subclass_of($class, AbstractDDDServiceProvider::class)) {
                $providers[] = $class::create($this->app);
            }
        }

        return $providers;
    }

    /**
     * Obtiene el FQCN(Fully Qualified Class Name) de un archivo PHP
     * crucial para que el autoloader encuentre y cargue la clase correcta
     */
    private function getClassFromFile(string $file): string
    {
        $contents = file_get_contents($file);

        preg_match('/namespace\s+(.+?);/', $contents, $namespace);
        preg_match('/class\s+(\w+)/', $contents, $class);

        return $namespace[1] . '\\' . $class[1];
    }
}
