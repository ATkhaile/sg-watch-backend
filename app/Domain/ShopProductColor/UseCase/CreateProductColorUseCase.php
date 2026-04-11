<?php

namespace App\Domain\ShopProductColor\UseCase;

use App\Domain\ShopProductColor\Repository\ShopProductColorRepository;

final class CreateProductColorUseCase
{
    private ShopProductColorRepository $repository;

    public function __construct(ShopProductColorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
