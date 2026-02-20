<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class NotificationsDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Notifications\Repository\NotificationsRepository::class,
            \App\Domain\Notifications\Infrastructure\DbNotificationsInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
