<?php

namespace App\Domain\DiscountCode\UseCase;

use App\Domain\DiscountCode\Repository\DiscountCodeRepository;

final class UpdateDiscountCodeUseCase
{
    private DiscountCodeRepository $repository;

    public function __construct(DiscountCodeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }
}
