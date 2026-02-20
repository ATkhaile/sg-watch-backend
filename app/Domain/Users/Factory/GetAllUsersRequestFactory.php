<?php

namespace App\Domain\Users\Factory;

use App\Domain\Users\Entity\UsersEntity;
use App\Http\Requests\Api\Users\GetAllUsersRequest;

class GetAllUsersRequestFactory
{
    public function createFromRequest(GetAllUsersRequest $request): UsersEntity
    {
        $sortOrder = [];
        $sortParams = ['sort_first_name', 'sort_email', 'sort_created', 'sort_updated'];

        foreach ($request->keys() as $key) {
            if (in_array($key, $sortParams) && !empty($request->input($key))) {
                $sortOrder[] = $key;
            }
        }

        return new UsersEntity(
            searchEmail: $request->input('search_email'),
            searchEmailNot: $request->input('search_email_not'),
            searchEmailLike: $request->input('search_email_like'),
            admin: $request->input('admin'),
            sortFirstName: $request->input('sort_first_name'),
            sortEmail: $request->input('sort_email'),
            sortCreated: $request->input('sort_created'),
            sortUpdated: $request->input('sort_updated'),
            sortOrder: $sortOrder,
            page: $request->input('page'),
            limit: $request->input('limit'),
            search: $request->input('search'),
        );
    }
}
