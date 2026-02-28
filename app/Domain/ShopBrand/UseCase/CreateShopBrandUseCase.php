<?php

namespace App\Domain\ShopBrand\UseCase;

use App\Domain\ShopBrand\Repository\ShopBrandRepository;

final class CreateShopBrandUseCase
{
    private ShopBrandRepository $repository;

    public function __construct(ShopBrandRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
