<?php

namespace App\Domain\Banner\Repository;

interface BannerRepository
{
    public function getList(array $filters): array;

    public function getById(int $id): ?array;

    public function create(array $data): array;

    public function update(int $id, array $data): array;

    public function delete(int $id): array;

    public function getPublicList(): array;
}
