<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopBrandDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopBrand\Repository\ShopBrandRepository::class,
            \App\Domain\ShopBrand\Infrastructure\DbShopBrandInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
