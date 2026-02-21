<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopProductDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopProduct\Repository\ShopProductRepository::class,
            \App\Domain\ShopProduct\Infrastructure\DbShopProductInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
