<?php

namespace App\Domain\ShopOrder\UseCase;

use App\Domain\ShopOrder\Repository\ShopOrderRepository;

final class AdminCreateOrderUseCase
{
    private ShopOrderRepository $repository;

    public function __construct(ShopOrderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->adminCreateOrder($data);
    }
}
