<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class AttachRoleToUserRequest extends FormRequest
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
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $failedRules = $validator->failed();
            $invalidRoles = [];

            foreach ($failedRules as $field => $rules) {
                if (str_contains($field, 'role_ids.')) {
                    $index = str_replace('role_ids.', '', $field);
                    $invalidRole = $this->input("role_ids.{$index}");
                    $invalidRoles[] = $invalidRole;
                    $validator->errors()->forget($field);
                }
            }

            if (!empty($invalidRoles)) {
                $validator->errors()->add(
                    'role_ids',
                    __('authorization.user.role_ids.invalid', [
                        'values' => implode(', ', array_unique($invalidRoles))
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
            'role_ids.required' => __('authorization.user.role_ids.required'),
            'role_ids.array' => __('authorization.user.role_ids.array'),
            'role_ids.*.required' => __('authorization.user.permission_ids.required'),
        ];
    }
}
