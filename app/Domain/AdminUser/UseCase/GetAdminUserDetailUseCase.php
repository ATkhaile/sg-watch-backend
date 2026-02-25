<?php

namespace App\Domain\AdminUser\UseCase;

use App\Domain\AdminUser\Repository\AdminUserRepository;

final class GetAdminUserDetailUseCase
{
    private AdminUserRepository $repository;

    public function __construct(AdminUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->getById($id);
    }
}
