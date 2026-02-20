<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;

class CreateUsersRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('users.validation.first_name.required'),
            'first_name.string' => __('users.validation.first_name.string'),
            'first_name.max' => __('users.validation.first_name.max'),
            'last_name.required' => __('users.validation.last_name.required'),
            'last_name.string' => __('users.validation.last_name.string'),
            'last_name.max' => __('users.validation.last_name.max'),
            'email.required' => __('users.validation.email.required'),
            'email.email' => __('users.validation.email.email'),
            'email.unique' => __('users.validation.email.unique'),
            'password.required' => __('users.validation.password.required'),
            'password.string' => __('users.validation.password.string'),
            'password.min' => __('users.validation.password.min'),
        ];
    }
}
