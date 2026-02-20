<?php

namespace App\Domain\Comment\UseCase;

use App\Domain\Shared\Concerns\RequiresPermission;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Domain\Comment\Repository\CommentRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;

final class GetAllCommentsUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'list-comment';

    public function __construct(
        private CommentRepository $commentRepository
    ) {
    }

    public function __invoke(CommentsEntity $entity): CommentsEntity
    {
        $this->authorize();

        $data = $this->commentRepository->getAllComments($entity);

        $comments = collect($data->items())->map(function ($comment) {
            return [
                'id' => $comment->id,
                'model' => $comment->model,
                'model_id' => $comment->model_id,
                'user_id' => $comment->user_id,
                'user' => $comment->user ? [
                    'id' => $comment->user->id,
                    'name' => $comment->user->name,
                    'email' => $comment->user->email,
                ] : null,
                'content' => $comment->content,
                'created_at' => $comment->created_at,
                'updated_at' => $comment->updated_at,
            ];
        })->toArray();

        $entity->setComments($comments);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
