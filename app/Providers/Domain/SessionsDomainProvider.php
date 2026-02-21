<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class SessionsDomainProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Sessions\Repository\SessionRepository::class,
            \App\Domain\Sessions\Infrastructure\DbSessionInfrastructure::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
