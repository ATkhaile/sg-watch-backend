<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AttachPermissionToRoleRequest extends FormRequest
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
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'exists:permissions,id',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $failedRules = $validator->failed();
            $invalidPermissions = [];

            foreach ($failedRules as $field => $rules) {
                if (str_contains($field, 'permission_ids.')) {
                    $index = str_replace('permission_ids.', '', $field);
                    $invalidPermission = $this->input("permission_ids.{$index}");
                    $invalidPermissions[] = $invalidPermission;
                    $validator->errors()->forget($field);
                }
            }

            if (!empty($invalidPermissions)) {
                $validator->errors()->add(
                    'permission_ids',
                    __('authorization.role.permission_ids.invalid', [
                        'values' => implode(', ', array_unique($invalidPermissions))
                    ])
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'role_id.required' => __('authorization.role.role_id.required'),
            'role_id.exists' => __('authorization.role.role_id.exists'),
            'permission_ids.required' => __('authorization.role.permission_ids.required'),
            'permission_ids.array' => __('authorization.role.permission_ids.array'),
            'permission_ids.*.required' => __('authorization.role.permission_ids.required'),
        ];
    }
}
