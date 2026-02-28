<?php

namespace App\Domain\ShopCategory\UseCase;

use App\Domain\ShopCategory\Repository\ShopCategoryRepository;

final class CreateShopCategoryUseCase
{
    private ShopCategoryRepository $repository;

    public function __construct(ShopCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
