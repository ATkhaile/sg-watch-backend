<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class AdminGetOrderListUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->adminGetList($filters);
    }
}
