<?php

namespace App\Domain\BigSale\Repository;

interface BigSaleRepository
{
    public function getList(array $filters): array;

    public function getById(int $id): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): array;

    public function delete(int $id): array;

    public function getPublicList(array $filters): array;

    public function getPublicDetail(int $id): ?array;
}
