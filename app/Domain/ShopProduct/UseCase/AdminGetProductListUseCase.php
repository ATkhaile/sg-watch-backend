<?php

namespace App\Domain\ShopProduct\UseCase;

use App\Domain\ShopProduct\Repository\ShopProductRepository;

final class AdminGetProductListUseCase
{
    private ShopProductRepository $repository;

    public function __construct(ShopProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->adminGetList($filters);
    }
}
