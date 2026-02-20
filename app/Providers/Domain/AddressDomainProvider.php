<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class AddressDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Address\Repository\AddressRepository::class,
            \App\Domain\Address\Infrastructure\DbAddressInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
