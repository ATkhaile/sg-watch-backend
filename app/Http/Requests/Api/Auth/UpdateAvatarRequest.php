<?php

namespace App\Http\Requests\Api\Auth;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateAvatarRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => __('Avatar is required'),
            'avatar.image' => __('File must be an image'),
            'avatar.mimes' => __('Avatar must be a file of type: jpeg, png, jpg, gif'),
            'avatar.max' => __('Avatar may not be greater than 50MB'),
        ];
    }
}
