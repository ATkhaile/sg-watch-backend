<?php

namespace App\Domain\Comment\UseCase;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Domain\Comment\Repository\CommentRepository;
use App\Enums\StatusCode;
use App\Components\CommonComponent;
use App\Domain\Shared\Concerns\RequiresPermission;

final class GetCommentsByModelUseCase
{
    use RequiresPermission;

    public const PERMISSION = 'view-user-comments';
    public function __construct(
        private CommentRepository $commentRepository
    ) {
    }

    public function __invoke(string $model, int $modelId, CommentsEntity $entity): CommentsEntity
    {
        $this->authorize();

        $data = $this->commentRepository->getCommentsByModel($model, $modelId, $entity);
        $totalComments = $this->commentRepository->getCommentCount($model, $modelId);

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
        $entity->setTotalComments($totalComments);
        $entity->setPagination(CommonComponent::getPaginationData($data));
        $entity->setStatus(StatusCode::OK);

        return $entity;
    }
}
