<?php

namespace App\Domain\Comment\Factory;

use App\Domain\Comment\Entity\DeleteCommentRequestEntity;
use App\Http\Requests\Api\Comment\DeleteCommentRequest;

class DeleteCommentRequestFactory
{
    public function createFromRequest(DeleteCommentRequest $request, int $commentId): DeleteCommentRequestEntity
    {
        return new DeleteCommentRequestEntity(
            commentId: $commentId
        );
    }
}
