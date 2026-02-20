<?php

namespace App\Domain\Comment\UseCase;

use App\Domain\Comment\Entity\CreateCommentRequestEntity;
use App\Domain\Comment\Entity\StatusEntity;
use App\Domain\Comment\Repository\CommentRepository;
use App\Enums\StatusCode;
use App\Domain\Shared\Concerns\RequiresPermission;

final class CreateCommentUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'create-user-comment';
    public function __construct(
        private CommentRepository $commentRepository
    ) {
    }

    public function __invoke(CreateCommentRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        $statusEntity = new StatusEntity();

        $result = $this->commentRepository->create($entity);

        if ($result) {
            $statusEntity->setStatus(StatusCode::CREATED);
        } else {
            $statusEntity->setStatus(StatusCode::INTERNAL_SERVER_ERROR);
        }

        return $statusEntity;
    }
}
