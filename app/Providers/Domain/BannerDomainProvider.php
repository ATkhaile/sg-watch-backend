<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class BannerDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Banner\Repository\BannerRepository::class,
            \App\Domain\Banner\Infrastructure\DbBannerInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
