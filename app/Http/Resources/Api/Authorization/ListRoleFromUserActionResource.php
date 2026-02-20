<?php

namespace App\Http\Resources\Api\Authorization;

use Illuminate\Http\Resources\Json\JsonResource;

class ListRoleFromUserActionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'success' => true,
            'message' => __('authorization.role.list.message'),
            'status_code' => $this->resource['status_code'],
            'data' => [
                'roles' => $this->resource['roles']->map(function ($role) {
                    return [
                        'id' => $role->id,
                        'name' => $role->name,
                        'display_name' => $role->display_name,
                        'description' => $role->description,
                    ];
                })->toArray()
            ]
        ];
    }
}
