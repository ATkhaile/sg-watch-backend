<?php

namespace App\Domain\ShopInventoryHistory\UseCase;

use App\Domain\ShopInventoryHistory\Repository\ShopInventoryHistoryRepository;

final class GetInventoryHistoryUseCase
{
    private ShopInventoryHistoryRepository $repository;

    public function __construct(ShopInventoryHistoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
