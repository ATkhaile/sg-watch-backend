<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class ListPermissionFromRoleActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('authorization.permission.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'permissions' => $this->resource['permissions']->map(function ($permission) {
                    return [
                        'id' => $permission->id,
                        'name' => $permission->name,
                        'display_name' => $permission->display_name,
                        'description' => $permission->description,
                        'domain' => $permission->domain,
                        'usecase_group' => $permission->usecase_group,
                    ];
                })->toArray()
            ]
        ];
    }
}
