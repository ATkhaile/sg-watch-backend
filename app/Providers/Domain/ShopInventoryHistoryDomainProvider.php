<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopInventoryHistoryDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopInventoryHistory\Repository\ShopInventoryHistoryRepository::class,
            \App\Domain\ShopInventoryHistory\Infrastructure\DbShopInventoryHistoryInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
