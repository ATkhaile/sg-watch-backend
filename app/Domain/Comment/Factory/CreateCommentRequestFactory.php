<?php

namespace App\Domain\Comment\Factory;

use App\Domain\Comment\Entity\CreateCommentRequestEntity;
use App\Http\Requests\Api\Comment\CreateCommentRequest;

class CreateCommentRequestFactory
{
    public function createFromRequest(CreateCommentRequest $request, int $userId): CreateCommentRequestEntity
    {
        return new CreateCommentRequestEntity(
            model: $request->input('model'),
            modelId: $request->input('model_id'),
            userId: $userId,
            content: $request->input('content')
        );
    }
}
