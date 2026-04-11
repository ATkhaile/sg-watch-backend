<?php

namespace App\Domain\ShopProductColor\UseCase;

use App\Domain\ShopProductColor\Repository\ShopProductColorRepository;

final class UpdateProductColorUseCase
{
    private ShopProductColorRepository $repository;

    public function __construct(ShopProductColorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }
}
