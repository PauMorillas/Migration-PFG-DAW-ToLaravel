<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\ServiceRepository;
use App\Repositories\Eloquent\BusinessRepository;
use App\Repositories\Contracts\ServiceRepositoryInterface;
use App\Repositories\Contracts\BusinessRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
        ServiceRepositoryInterface::class,
        ServiceRepository::class
        );

        $this->app->bind(
            BusinessRepositoryInterface::class,
            BusinessRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
