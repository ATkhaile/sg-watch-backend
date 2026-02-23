<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class ShopReviewDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\ShopReview\Repository\ShopReviewRepository::class,
            \App\Domain\ShopReview\Infrastructure\DbShopReviewInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
