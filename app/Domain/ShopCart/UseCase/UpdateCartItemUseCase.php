<?php

namespace App\Domain\ShopCart\UseCase;

use App\Domain\ShopCart\Repository\ShopCartRepository;

final class UpdateCartItemUseCase
{
    private ShopCartRepository $repository;

    public function __construct(ShopCartRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(?int $userId, ?string $deviceId, int $cartItemId, int $quantity): array
    {
        return $this->repository->updateItemQuantity($userId, $deviceId, $cartItemId, $quantity);
    }
}
