<?php

namespace App\Domain\DiscountCode\UseCase;

use App\Domain\DiscountCode\Repository\DiscountCodeRepository;

final class CreateDiscountCodeUseCase
{
    private DiscountCodeRepository $repository;

    public function __construct(DiscountCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $data): array
    {
        return $this->repository->create($data);
    }
}
