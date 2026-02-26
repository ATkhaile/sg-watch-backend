<?php

namespace App\Domain\DiscountCode\UseCase;

use App\Domain\DiscountCode\Repository\DiscountCodeRepository;

final class GetDiscountCodeListUseCase
{
    private DiscountCodeRepository $repository;

    public function __construct(DiscountCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
