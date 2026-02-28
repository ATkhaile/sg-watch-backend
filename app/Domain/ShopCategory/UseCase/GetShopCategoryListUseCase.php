<?php

namespace App\Domain\ShopCategory\UseCase;

use App\Domain\ShopCategory\Repository\ShopCategoryRepository;

final class GetShopCategoryListUseCase
{
    private ShopCategoryRepository $repository;

    public function __construct(ShopCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
