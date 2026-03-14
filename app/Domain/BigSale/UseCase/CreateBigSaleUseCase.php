<?php

namespace App\Domain\BigSale\UseCase;

use App\Domain\BigSale\Repository\BigSaleRepository;

final class CreateBigSaleUseCase
{
    private BigSaleRepository $repository;

    public function __construct(BigSaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
