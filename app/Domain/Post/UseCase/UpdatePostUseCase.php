<?php

namespace App\Domain\Post\UseCase;

use App\Domain\Post\Repository\PostRepository;

final class UpdatePostUseCase
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $data): array
    {
        return $this->repository->update($id, $data);
    }
}
