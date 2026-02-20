<?php

namespace App\Http\Responders\Api\Comment;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Http\Resources\Api\Comment\CommentsResource;

final class CommentsResponder
{
    public function __invoke(CommentsEntity $entity): CommentsResource
    {
        $resourceAry = $this->makeResource($entity);
        return new CommentsResource($resourceAry);
    }

    private function makeResource(CommentsEntity $entity): array
    {
        return [
            'status_code' => $entity->getStatus(),
            'message' => 'Success',
            'comments' => $entity->getComments(),
            'total_comments' => $entity->getTotalComments(),
            'pagination' => $entity->getPagination(),
        ];
    }
}
