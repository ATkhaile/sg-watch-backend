<?php

namespace App\Domain\AdminUser\UseCase;

use App\Domain\AdminUser\Repository\AdminUserRepository;

final class DeleteAdminUserUseCase
{
    private AdminUserRepository $repository;

    public function __construct(AdminUserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, int $currentUserId): array
    {
        return $this->repository->delete($id, $currentUserId);
    }
}
