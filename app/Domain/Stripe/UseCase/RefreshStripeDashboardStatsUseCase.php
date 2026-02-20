<?php

namespace App\Domain\Stripe\UseCase;

use App\Domain\Stripe\Repository\StripeRepository;
use App\Domain\Shared\Concerns\RequiresPermission;

final class RefreshStripeDashboardStatsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-stripe-dashboard';
    public function __construct(
        private StripeRepository $stripeRepository
    ) {}

    public function handle(int $id): array
    {
        $this->authorize();

        return $this->stripeRepository->refreshDashboardStats($id);
    }

    public function handleAll(): array
    {
        $this->authorize();

        return $this->stripeRepository->refreshAllDashboardStats();
    }
}
