<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class BigSaleDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\BigSale\Repository\BigSaleRepository::class,
            \App\Domain\BigSale\Infrastructure\DbBigSaleInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
