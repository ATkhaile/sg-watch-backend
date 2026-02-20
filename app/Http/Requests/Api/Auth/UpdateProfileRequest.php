<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => [
                'sometimes',
                'string',
                'max:50',
            ],
            'last_name' => [
                'sometimes',
                'string',
                'max:50',
            ],
            'gender' => [
                'sometimes',
                'nullable',
                Rule::in(['male', 'female', 'other', 'unknown']),
            ],
            'birthday' => [
                'sometimes',
                'nullable',
                'date',
                'date_format:Y-m-d',
                'before:today',
            ],
            'avatar_url' => [
                'sometimes',
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.max' => __('auth.validation.first_name.max'),
            'last_name.max' => __('auth.validation.last_name.max'),
            'gender.in' => __('auth.validation.gender.in'),
            'birthday.date' => __('auth.validation.birthday.date'),
            'birthday.date_format' => __('auth.validation.birthday.date_format'),
            'birthday.before' => __('auth.validation.birthday.before'),
            'avatar_url.max' => __('auth.validation.avatar_url.max'),
        ];
    }
}
