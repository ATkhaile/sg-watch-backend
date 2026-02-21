<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class CancelOrderUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $orderId, ?string $reason): array
    {
        return $this->repository->cancel($userId, $orderId, $reason);
    }
}
