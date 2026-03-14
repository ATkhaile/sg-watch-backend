<?php

namespace App\Domain\BigSale\UseCase;

use App\Domain\BigSale\Repository\BigSaleRepository;

final class UpdateBigSaleUseCase
{
    private BigSaleRepository $repository;

    public function __construct(BigSaleRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }
}
