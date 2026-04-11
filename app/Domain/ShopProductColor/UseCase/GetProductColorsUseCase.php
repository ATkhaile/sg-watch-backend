<?php

namespace App\Domain\ShopProductColor\UseCase;

use App\Domain\ShopProductColor\Repository\ShopProductColorRepository;

final class GetProductColorsUseCase
{
    private ShopProductColorRepository $repository;

    public function __construct(ShopProductColorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $productId): array
    {
        return $this->repository->getByProductId($productId);
    }
}
