<?php

namespace App\Domain\ShopProduct\UseCase;

use App\Domain\ShopProduct\Repository\ShopProductRepository;

final class AdminGetFeaturedProductsUseCase
{
    private ShopProductRepository $repository;

    public function __construct(ShopProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(?int $userId = null): array
    {
        return $this->repository->getFeaturedProducts($userId);
    }
}
