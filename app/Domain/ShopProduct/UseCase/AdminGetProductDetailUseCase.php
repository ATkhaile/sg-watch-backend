<?php

namespace App\Domain\ShopProduct\UseCase;

use App\Domain\ShopProduct\Repository\ShopProductRepository;

final class AdminGetProductDetailUseCase
{
    private ShopProductRepository $repository;

    public function __construct(ShopProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->adminGetById($id);
    }
}
