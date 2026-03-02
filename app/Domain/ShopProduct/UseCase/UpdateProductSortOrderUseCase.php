<?php

namespace App\Domain\ShopProduct\UseCase;

use App\Domain\ShopProduct\Repository\ShopProductRepository;

final class UpdateProductSortOrderUseCase
{
    private ShopProductRepository $repository;

    public function __construct(ShopProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $products): array
    {
        return $this->repository->updateProductSortOrder($products);
    }
}
