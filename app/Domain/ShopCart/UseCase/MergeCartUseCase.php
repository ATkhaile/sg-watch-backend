<?php

namespace App\Domain\ShopCart\UseCase;

use App\Domain\ShopCart\Repository\ShopCartRepository;

final class MergeCartUseCase
{
    private ShopCartRepository $repository;

    public function __construct(ShopCartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(string $deviceId, int $userId): void
    {
        $this->repository->mergeCarts($deviceId, $userId);
    }
}
