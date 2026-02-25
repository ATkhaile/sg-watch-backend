<?php

namespace App\Domain\AdminUser\UseCase;

use App\Domain\AdminUser\Repository\AdminUserRepository;

final class UpdateAdminUserUseCase
{
    private AdminUserRepository $repository;

    public function __construct(AdminUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }
}
