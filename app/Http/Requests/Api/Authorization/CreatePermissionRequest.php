<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class CreatePermissionRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[^\s]+$/|unique:permissions,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'domain' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('authorization.permission.validation.name.required'),
            'name.unique' => __('authorization.permission.validation.name.unique'),
            'name.string' => __('authorization.permission.validation.name.string'),
            'name.max' => __('authorization.permission.validation.name.max'),
            'name.regex' => __('authorization.permission.validation.name.regex'),
            'display_name.required' => __('authorization.permission.validation.display_name.required'),
            'display_name.string' => __('authorization.permission.validation.display_name.string'),
            'display_name.max' => __('authorization.permission.validation.display_name.max'),
            'description.string' => __('authorization.permission.validation.description.string'),
            'domain.required' => __('authorization.permission.validation.domain.required'),
            'domain.string' => __('authorization.permission.validation.description.string'),
        ];
    }
}
