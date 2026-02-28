<?php

namespace App\Domain\ShopBrand\UseCase;

use App\Domain\ShopBrand\Repository\ShopBrandRepository;

final class GetShopBrandListUseCase
{
    private ShopBrandRepository $repository;

    public function __construct(ShopBrandRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
