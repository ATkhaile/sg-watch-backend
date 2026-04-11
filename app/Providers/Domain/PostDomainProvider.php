<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class PostDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Post\Repository\PostRepository::class,
            \App\Domain\Post\Infrastructure\DbPostInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
