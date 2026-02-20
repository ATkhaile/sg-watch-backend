<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class GoogleDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Google\Repository\GoogleRepository::class,
            \App\Domain\Google\Infrastructure\DbGoogleInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
