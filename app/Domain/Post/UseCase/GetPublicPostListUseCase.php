<?php

namespace App\Domain\Post\UseCase;

use App\Domain\Post\Repository\PostRepository;

final class GetPublicPostListUseCase
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getPublicList($filters);
    }
}
