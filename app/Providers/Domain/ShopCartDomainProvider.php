<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopCartDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopCart\Repository\ShopCartRepository::class,
            \App\Domain\ShopCart\Infrastructure\DbShopCartInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
