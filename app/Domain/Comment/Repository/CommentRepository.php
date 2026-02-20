<?php

namespace App\Domain\Comment\Repository;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Domain\Comment\Entity\CreateCommentRequestEntity;
use App\Domain\Comment\Entity\DeleteCommentRequestEntity;
use Illuminate\Pagination\LengthAwarePaginator;

interface CommentRepository
{
    /**
     * Get all comments with filters
     */
    public function getAllComments(CommentsEntity $entity): LengthAwarePaginator;

    /**
     * Get comments by model and model_id
     */
    public function getCommentsByModel(string $model, int $modelId, CommentsEntity $entity): LengthAwarePaginator;

    /**
     * Get total comment count by model and model_id
     */
    public function getCommentCount(string $model, int $modelId): int;

    /**
     * Create a new comment
     */
    public function create(CreateCommentRequestEntity $requestEntity): bool;

    /**
     * Delete a comment
     */
    public function delete(DeleteCommentRequestEntity $requestEntity): bool;
}
