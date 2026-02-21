<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class AdminUpdateOrderStatusUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $orderId, string $status, array $extra = []): array
    {
        return $this->repository->adminUpdateStatus($orderId, $status, $extra);
    }
}
