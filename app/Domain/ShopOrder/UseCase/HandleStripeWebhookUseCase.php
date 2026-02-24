<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class HandleStripeWebhookUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $payload, string $signature): array
    {
        return $this->repository->handleStripeWebhook($payload, $signature);
    }
}
