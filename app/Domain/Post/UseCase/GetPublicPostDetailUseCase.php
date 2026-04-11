<?php

namespace App\Domain\Post\UseCase;

use App\Domain\Post\Repository\PostRepository;

final class GetPublicPostDetailUseCase
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(int $id): ?array
    {
        return $this->repository->getPublicDetail($id);
    }
}
