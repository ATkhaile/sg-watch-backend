<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\RolesEntity;
use App\Http\Requests\Api\Authorization\GetAllRoleRequest;

class GetAllRoleRequestFactory
{
    public function createFromRequest(GetAllRoleRequest $request): RolesEntity
    {
        $sortOrder = [];
        $sortParams = ['sort_name', 'sort_created', 'sort_updated'];

        foreach ($request->keys() as $key) {
            if (in_array($key, $sortParams) && !empty($request->input($key))) {
                $sortOrder[] = $key;
            }
        }

        return new RolesEntity(
            searchName: $request->input('search_name'),
            searchNameNot: $request->input('search_name_not'),
            searchNameLike: $request->input('search_name_like'),
            page: $request->input('page'),
            limit: $request->input('limit'),
            sortName: $request->input('sort_name'),
            sortCreated: $request->input('sort_created'),
            sortUpdated: $request->input('sort_updated'),
            sortOrder: $sortOrder
        );
    }
}
