<?php

namespace App\Domain\BigSale\UseCase;

use App\Domain\BigSale\Repository\BigSaleRepository;

final class GetBigSaleListUseCase
{
    private BigSaleRepository $repository;

    public function __construct(BigSaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
