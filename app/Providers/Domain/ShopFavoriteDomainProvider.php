<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopFavoriteDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopFavorite\Repository\ShopFavoriteRepository::class,
            \App\Domain\ShopFavorite\Infrastructure\DbShopFavoriteInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
