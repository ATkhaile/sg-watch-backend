<?php

namespace App\Providers\Domain;

use Illuminate\Support\ServiceProvider;

class CommentDomainProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\Comment\Repository\CommentRepository::class,
            \App\Domain\Comment\Infrastructure\DbCommentInfrastructure::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
