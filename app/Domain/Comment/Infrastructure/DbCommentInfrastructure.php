<?php

namespace App\Domain\Comment\Infrastructure;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Domain\Comment\Entity\CreateCommentRequestEntity;
use App\Domain\Comment\Entity\DeleteCommentRequestEntity;
use App\Domain\Comment\Repository\CommentRepository;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;

class DbCommentInfrastructure implements CommentRepository
{
    private Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    public function getAllComments(CommentsEntity $requestEntity): LengthAwarePaginator
    {
        $query = Comment::with('user:id,first_name,last_name,email');

        // Filter by model if provided
        if ($model = $requestEntity->getSearchModel()) {
            $query->where('model', $model);
        }

        // Filter by model_id if provided
        if ($modelId = $requestEntity->getSearchModelId()) {
            $query->where('model_id', $modelId);
        }

        // Filter by content if provided (LIKE search)
        if ($searchContent = $requestEntity->getSearchContent()) {
            $query->where('content', 'LIKE', '%' . $searchContent . '%');
        }

        // Sort
        $sortOrder = $requestEntity->getSortOrder();
        if ($sortOrder && !empty($sortOrder)) {
            foreach ($sortOrder as $param) {
                switch ($param) {
                    case 'sort_created':
                        if ($sortCreated = $requestEntity->getSortCreated()) {
                            $query->orderBy('created_at', $sortCreated);
                        }
                        break;
                    case 'sort_updated':
                        if ($sortUpdated = $requestEntity->getSortUpdated()) {
                            $query->orderBy('updated_at', $sortUpdated);
                        }
                        break;
                }
            }
        } else {
            // Default sort by created_at DESC
            $query->orderBy('created_at', 'DESC');
        }

        return $query->paginate(
            $requestEntity->getLimit() ?? 10,
            ['*'],
            'page',
            $requestEntity->getPage() ?? 1
        );
    }

    public function getCommentsByModel(string $model, int $modelId, CommentsEntity $requestEntity): LengthAwarePaginator
    {
        $query = Comment::with('user:id,first_name,last_name,email')
            ->where('model', $model)
            ->where('model_id', $modelId);

        // Sort
        $sortOrder = $requestEntity->getSortOrder();
        if ($sortOrder && !empty($sortOrder)) {
            foreach ($sortOrder as $param) {
                switch ($param) {
                    case 'sort_created':
                        if ($sortCreated = $requestEntity->getSortCreated()) {
                            $query->orderBy('created_at', $sortCreated);
                        }
                        break;
                    case 'sort_updated':
                        if ($sortUpdated = $requestEntity->getSortUpdated()) {
                            $query->orderBy('updated_at', $sortUpdated);
                        }
                        break;
                }
            }
        } else {
            // Default sort by created_at DESC
            $query->orderBy('created_at', 'DESC');
        }

        return $query->paginate(
            $requestEntity->getLimit() ?? 10,
            ['*'],
            'page',
            $requestEntity->getPage() ?? 1
        );
    }

    public function getCommentCount(string $model, int $modelId): int
    {
        return Comment::where('model', $model)
            ->where('model_id', $modelId)
            ->count();
    }

    public function create(CreateCommentRequestEntity $requestEntity): bool
    {
        try {
            Comment::create([
                'model' => $requestEntity->getModel(),
                'model_id' => $requestEntity->getModelId(),
                'user_id' => $requestEntity->getUserId(),
                'content' => $requestEntity->getContent(),
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(DeleteCommentRequestEntity $requestEntity): bool
    {
        try {
            $comment = Comment::find($requestEntity->getCommentId());
            if (!$comment) {
                return false;
            }

            $comment->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
