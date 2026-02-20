<?php

namespace App\Domain\Authorization\Factory;

use App\Domain\Authorization\Entity\PermissionsEntity;
use App\Http\Requests\Api\Authorization\GetAllPermissionRequest;

class GetAllPermissionRequestFactory
{
    public function createFromRequest(GetAllPermissionRequest $request): PermissionsEntity
    {
        $sortOrder = [];
        $sortParams = ['sort_name', 'sort_usecase_group', 'sort_created', 'sort_updated'];

        foreach ($request->keys() as $key) {
            if (in_array($key, $sortParams) && !empty($request->input($key))) {
                $sortOrder[] = $key;
            }
        }

        return new PermissionsEntity(
            searchName: $request->input('search_name'),
            searchNameNot: $request->input('search_name_not'),
            searchNameLike: $request->input('search_name_like'),
            searchUsecaseGroup: $request->input('search_usecase_group'),
            searchUsecaseGroupNot: $request->input('search_usecase_group_not'),
            searchUsecaseGroupLike: $request->input('search_usecase_group_like'),
            usecaseGroup: $request->input('usecase_group'),
            page: $request->input('page'),
            limit: $request->input('limit'),
            sortName: $request->input('sort_name'),
            sortUsecaseGroup: $request->input('sort_usecase_group'),
            sortCreated: $request->input('sort_created'),
            sortUpdated: $request->input('sort_updated'),
            sortOrder: $sortOrder
        );
    }
}
