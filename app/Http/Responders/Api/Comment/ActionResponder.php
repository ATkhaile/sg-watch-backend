<?php

namespace App\Http\Responders\Api\Comment;

use App\Domain\Comment\Entity\StatusEntity;
use App\Http\Resources\Api\Comment\ActionResource;

final class ActionResponder
{
    public function __invoke(StatusEntity $statusEntity): ActionResource
    {
        $resourceAry = $this->makeResource($statusEntity);
        return new ActionResource($resourceAry);
    }

    private function makeResource(StatusEntity $statusEntity): array
    {
        return [
            'status_code' => $statusEntity->getStatus(),
            'message' => $this->getMessage($statusEntity->getStatus()),
        ];
    }

    private function getMessage(int $statusCode): string
    {
        return match ($statusCode) {
            200 => 'Comment deleted successfully',
            201 => 'Comment created successfully',
            404 => 'Comment not found',
            500 => 'Failed to process comment',
            default => 'Unknown status',
        };
    }
}
