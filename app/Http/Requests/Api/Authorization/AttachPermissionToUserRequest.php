<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AttachPermissionToUserRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $this->merge(['user_id' => $this->route('user_id')]);

        return [
            'user_id' => [
                'required',
                Rule::exists('users', 'id')->where(function ($query) {
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
                    __('authorization.user.permission_ids.invalid', [
                        'values' => implode(', ', array_unique($invalidPermissions))
                    ])
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __('authorization.user.user_id.required'),
            'user_id.exists' => __('authorization.user.user_id.exists'),
            'permission_ids.required' => __('authorization.user.permission_ids.required'),
            'permission_ids.array' => __('authorization.user.permission_ids.array'),
            'permission_ids.*.required' => __('authorization.user.permission_ids.required'),
        ];
    }
}
