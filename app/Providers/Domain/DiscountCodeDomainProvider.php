<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class DiscountCodeDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\DiscountCode\Repository\DiscountCodeRepository::class,
            \App\Domain\DiscountCode\Infrastructure\DbDiscountCodeInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
