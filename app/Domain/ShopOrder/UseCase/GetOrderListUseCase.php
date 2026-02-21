<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class GetOrderListUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $userId, ?string $status, int $perPage): array
    {
        return $this->repository->getList($userId, $status, $perPage);
    }
}
