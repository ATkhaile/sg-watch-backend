<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'domain' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'display_name.string' => __('authorization.role.validation.title_string'),
            'display_name.max' => __('authorization.role.validation.name_max'),
            'description.string' => __('authorization.role.validation.description_string'),
            'domain.string' => __('authorization.role.validation.domain_string'),
            'domain.max' => __('authorization.role.validation.domain_max'),
        ];
    }
}
