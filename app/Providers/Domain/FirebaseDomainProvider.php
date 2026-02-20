<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class FirebaseDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Firebase\Repository\FirebaseRepository::class,
            \App\Domain\Firebase\Infrastructure\DbFirebaseInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
