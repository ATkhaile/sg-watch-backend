<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class GetOrderDetailUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, int $orderId): ?array
    {
        return $this->repository->getDetail($userId, $orderId);
    }
}
