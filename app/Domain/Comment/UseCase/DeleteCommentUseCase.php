<?php

namespace App\Domain\Comment\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Comment\Entity\DeleteCommentRequestEntity;
use App\Domain\Comment\Entity\StatusEntity;
use App\Domain\Comment\Repository\CommentRepository;
use App\Enums\StatusCode;

final class DeleteCommentUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'delete-comment';

    public function __construct(
        private CommentRepository $commentRepository
    ) {
    }

    public function __invoke(DeleteCommentRequestEntity $entity): StatusEntity
    {
        $this->authorize();

        $statusEntity = new StatusEntity();

        $result = $this->commentRepository->delete($entity);

        if ($result) {
            $statusEntity->setStatus(StatusCode::OK);
        } else {
            $statusEntity->setStatus(StatusCode::NOT_FOUND);
        }

        return $statusEntity;
    }
}
