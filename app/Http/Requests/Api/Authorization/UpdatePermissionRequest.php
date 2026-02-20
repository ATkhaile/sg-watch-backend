<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'display_name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'display_name.string' => __('authorization.permission.validation.title_string'),
            'display_name.max' => __('authorization.permission.validation.name_max'),
            'description.string' => __('authorization.permission.validation.description_string'),
        ];
    }
}
