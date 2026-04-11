<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopProductColorDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopProductColor\Repository\ShopProductColorRepository::class,
            \App\Domain\ShopProductColor\Infrastructure\DbShopProductColorInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
