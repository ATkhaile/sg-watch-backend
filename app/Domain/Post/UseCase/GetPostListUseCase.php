<?php

namespace App\Domain\Post\UseCase;

use App\Domain\Post\Repository\PostRepository;

final class GetPostListUseCase
{
    private PostRepository $repository;

    public function __construct(PostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(array $filters): array
    {
        return $this->repository->getList($filters);
    }
}
