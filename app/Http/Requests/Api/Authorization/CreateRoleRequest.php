<?php

namespace App\Http\Requests\Api\Authorization;

use Illuminate\Foundation\Http\FormRequest;

class CreateRoleRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|regex:/^[^\s]+$/|unique:roles,name',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('authorization.role.validation.name.required'),
            'name.unique' => __('authorization.role.validation.name.unique'),
            'name.string' => __('authorization.role.validation.name.string'),
            'name.max' => __('authorization.role.validation.name.max'),
            'name.regex' => __('authorization.role.validation.name.regex'),
            'display_name.required' => __('authorization.role.validation.display_name.required'),
            'display_name.string' => __('authorization.role.validation.display_name.string'),
            'display_name.max' => __('authorization.role.validation.display_name.max'),
            'description.string' => __('authorization.role.validation.description.string'),
        ];
    }
}
