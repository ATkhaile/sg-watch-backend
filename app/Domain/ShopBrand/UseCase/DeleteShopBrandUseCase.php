<?php

namespace App\Domain\ShopBrand\UseCase;

use App\Domain\ShopBrand\Repository\ShopBrandRepository;

final class DeleteShopBrandUseCase
{
    private ShopBrandRepository $repository;

    public function __construct(ShopBrandRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): array
    {
        return $this->repository->delete($id);
    }
}
