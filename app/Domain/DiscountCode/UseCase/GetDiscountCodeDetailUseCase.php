<?php

namespace App\Domain\DiscountCode\UseCase;

use App\Domain\DiscountCode\Repository\DiscountCodeRepository;

final class GetDiscountCodeDetailUseCase
{
    private DiscountCodeRepository $repository;

    public function __construct(DiscountCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->getById($id);
    }
}
