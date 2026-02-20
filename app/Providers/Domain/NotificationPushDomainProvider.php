<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class NotificationPushDomainProvider  extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\NotificationPush\Repository\NotificationPushRepository::class,
            \App\Domain\NotificationPush\Infrastructure\DbNotificationPushInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
