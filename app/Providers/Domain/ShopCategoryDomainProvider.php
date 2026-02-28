<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopCategoryDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopCategory\Repository\ShopCategoryRepository::class,
            \App\Domain\ShopCategory\Infrastructure\DbShopCategoryInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
