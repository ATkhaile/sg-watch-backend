<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class NoticeDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Notice\Repository\NoticeRepository::class,
            \App\Domain\Notice\Infrastructure\DbNoticeInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
