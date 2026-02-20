<?php

namespace App\Domain\ShopProduct\Repository;

interface ShopProductRepository
{
    public function getList(array $filters): array;
}
