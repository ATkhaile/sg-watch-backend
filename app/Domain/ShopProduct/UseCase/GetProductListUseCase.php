<?php

namespace App\Domain\ShopProduct\UseCase;

use App\Domain\ShopProduct\Repository\ShopProductRepository;

final class GetProductListUseCase
{
    private ShopProductRepository $repository;

    public function __construct(ShopProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters, ?int $userId = null): array
    {
        return $this->repository->getList($filters, $userId);
    }
}
