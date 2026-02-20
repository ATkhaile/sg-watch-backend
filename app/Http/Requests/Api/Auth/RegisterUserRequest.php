<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\User;
use Illuminate\Validation\Rule;

class RegisterUserRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:50',
            ],
            'last_name' => [
                'required',
                'string',
                'max:50',
            ],
            'email' => [
                'required',
                'max:255',
                'email',
                'unique:users,email'
            ],
            'password' => 'required|min:8|max:16|confirmed',
            'password_confirmation' => 'required|min:8|max:16|same:password',
            'invite_code' => [
                'nullable',
                'string',
                'max:255',
                Rule::exists(User::class, 'invite_code')->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => __('auth.validation.first_name.required'),
            'first_name.max' => __('auth.validation.first_name.max'),
            'last_name.required' => __('auth.validation.last_name.required'),
            'last_name.max' => __('auth.validation.last_name.max'),
            'email.required' => __('auth.validation.email.required'),
            'email.max' => __('auth.validation.email.max'),
            'email.email' => __('auth.validation.email.email'),
            'email.unique' => __('auth.validation.email.unique'),
            'password.required' => __('auth.validation.password.required'),
            'password.min' => __('auth.validation.password.min'),
            'password.max' => __('auth.validation.password.max'),
            'password.confirmed' => __('auth.validation.password.confirmed'),
            'password_confirmation.required' => __('auth.validation.password_confirmation.required'),
            'password_confirmation.same' => __('auth.validation.password_confirmation.same'),
            'invite_code.string' => __('auth.validation.invite_code.string'),
            'invite_code.max' => __('auth.validation.invite_code.max'),
            'invite_code.exists' => __('auth.validation.invite_code.exists'),
        ];
    }
}
