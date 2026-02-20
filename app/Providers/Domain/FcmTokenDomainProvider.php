<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class FcmTokenDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\FcmToken\Repository\FcmTokenRepository::class,
            \App\Domain\FcmToken\Infrastructure\DbFcmTokenInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
