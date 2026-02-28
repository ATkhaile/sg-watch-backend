<?php

namespace App\Domain\ShopCategory\UseCase;

use App\Domain\ShopCategory\Repository\ShopCategoryRepository;

final class UpdateShopCategoryUseCase
{
    private ShopCategoryRepository $repository;

    public function __construct(ShopCategoryRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }
}
