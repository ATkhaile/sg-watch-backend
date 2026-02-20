<?php

namespace App\Http\Requests\Api\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUsersRequest extends FormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $userId = $this->route('id');

        return [
            'first_name' => 'nullable|string|max:50',
            'last_name' => 'nullable|string|max:50',
            'email' => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($userId),
            ],
            'gender' => 'nullable|string|in:male,female,other,unknown',
            'password' => 'nullable|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.string' => __('users.validation.first_name.string'),
            'first_name.max' => __('users.validation.first_name.max'),
            'last_name.string' => __('users.validation.last_name.string'),
            'last_name.max' => __('users.validation.last_name.max'),
            'email.email' => __('users.validation.email.email'),
            'email.unique' => __('users.validation.email.unique'),
            'gender.in' => __('users.validation.gender.in'),
            'password.string' => __('users.validation.password.string'),
            'password.min' => __('users.validation.password.min'),
        ];
    }
}
