<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class ListPermissionFromUserActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('authorization.permission.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'permissions' => array_map(function ($permission) {
                    return [
                        'id' => $permission['id'],
                        'name' => $permission['name'],
                        'display_name' => $permission['display_name'],
                        'description' => $permission['description'],
                        'usecase_group' => $permission['usecase_group'],
                        'source' => $permission['source'],
                        'source_name' => $permission['source_name'],
                    ];
                }, $this->resource['permissions'])
            ]
        ];
    }
}
