<?php

namespace App\Providers;

use App\DDD\Backoffice\Booking\Domain\Repository\PreBookingRepositoryV2Interface;
use App\DDD\Backoffice\Booking\Domain\Service\PreBookingServiceV2;
use App\DDD\Backoffice\Booking\Infrastructure\Persistence\EloquentPreBookingRepository;
use App\DDD\Backoffice\Shared\Domain\Mail\MailerInterface;
use App\DDD\Backoffice\Shared\Infrastructure\Mail\LaravelMailer;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\PreBookingRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\PreBookingRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\ServiceRepository;
use App\Repositories\Eloquent\BusinessRepository;
use App\Repositories\Eloquent\UserRepository;
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

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            PreBookingRepositoryInterface::class,
            PreBookingRepository::class
        );

        $this->app->bind(
            BookingRepositoryInterface::class,
            BookingRepository::class
        );

        // Repo de la parte hecha aplicando DDD
        $this->app->bind(
            PreBookingRepositoryV2Interface::class,
            EloquentPreBookingRepository::class
        );

        $this->app->bind(
            MailerInterface::class,
            LaravelMailer::class
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
