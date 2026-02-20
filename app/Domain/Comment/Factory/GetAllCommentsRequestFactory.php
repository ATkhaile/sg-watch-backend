<?php

namespace App\Domain\Comment\Factory;

use App\Domain\Comment\Entity\CommentsEntity;
use App\Http\Requests\Api\Comment\GetAllCommentsRequest;

class GetAllCommentsRequestFactory
{
    public function createFromRequest(GetAllCommentsRequest $request): CommentsEntity
    {
        $sortOrder = [];
        $sortParams = ['sort_created', 'sort_updated'];

        foreach ($request->keys() as $key) {
            if (in_array($key, $sortParams) && !empty($request->input($key))) {
                $sortOrder[] = $key;
            }
        }

        return new CommentsEntity(
            searchModel: $request->input('model'),
            searchModelId: $request->input('model_id'),
            searchContent: $request->input('search_content'),
            page: $request->input('page'),
            limit: $request->input('limit'),
            sortCreated: $request->input('sort_created'),
            sortUpdated: $request->input('sort_updated'),
            sortOrder: $sortOrder
        );
    }
}
