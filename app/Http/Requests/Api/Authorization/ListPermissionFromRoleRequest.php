<?php

namespace App\Http\Requests\Api\Authorization;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class ListPermissionFromRoleRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['role_id' => $this->route('role_id')]);

        return [
            'role_id' => [
                'required',
                Rule::exists('roles', 'id')->where(function ($query) {
                    $query->whereNull('deleted_at');
                })
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'role_id.required' => __('authorization.role.role_id.required'),
            'role_id.exists' => __('authorization.role.role_id.exists'),
        ];
    }
}
