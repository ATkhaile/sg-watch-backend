<?php

namespace App\Domain\DiscountCode\UseCase;

use App\Domain\DiscountCode\Repository\DiscountCodeRepository;

final class GetDiscountCodeByCodeUseCase
{
    private DiscountCodeRepository $repository;

    public function __construct(DiscountCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $code): ?array
    {
        return $this->repository->getByCode($code);
    }
}
