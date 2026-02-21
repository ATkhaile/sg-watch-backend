<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopOrderDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopOrder\Repository\ShopOrderRepository::class,
            \App\Domain\ShopOrder\Infrastructure\DbShopOrderInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
