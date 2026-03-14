<?php

namespace App\Domain\BigSale\UseCase;

use App\Domain\BigSale\Repository\BigSaleRepository;

final class GetPublicBigSaleDetailUseCase
{
    private BigSaleRepository $repository;

    public function __construct(BigSaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->getPublicDetail($id);
    }
}
