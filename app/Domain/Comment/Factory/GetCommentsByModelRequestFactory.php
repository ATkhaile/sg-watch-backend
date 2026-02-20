<?php

namespace App\Domain\Comment\Factory;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Http\Requests\Api\Comment\GetCommentsByModelRequest;

class GetCommentsByModelRequestFactory
{
    public function createFromRequest(GetCommentsByModelRequest $request): CommentsEntity
    {
        $sortOrder = [];
        $sortParams = ['sort_created', 'sort_updated'];

        foreach ($request->keys() as $key) {
            if (in_array($key, $sortParams) && !empty($request->input($key))) {
                $sortOrder[] = $key;
            }
        }

        return new CommentsEntity(
            page: $request->input('page'),
            limit: $request->input('limit'),
            sortCreated: $request->input('sort_created'),
            sortUpdated: $request->input('sort_updated'),
            sortOrder: $sortOrder
        );
    }
}
