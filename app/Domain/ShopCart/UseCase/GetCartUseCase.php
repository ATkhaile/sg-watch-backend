<?php

namespace App\Domain\ShopCart\UseCase;

use App\Domain\ShopCart\Repository\ShopCartRepository;

final class GetCartUseCase
{
    private ShopCartRepository $repository;

    public function __construct(ShopCartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(?int $userId, ?string $deviceId): array
    {
        return $this->repository->getCart($userId, $deviceId);
    }
}
