<?php

namespace App\Domain\ShopProductColor\UseCase;

use App\Domain\ShopProductColor\Repository\ShopProductColorRepository;

final class DeleteProductColorUseCase
{
    private ShopProductColorRepository $repository;

    public function __construct(ShopProductColorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): array
    {
        return $this->repository->delete($id);
    }
}
