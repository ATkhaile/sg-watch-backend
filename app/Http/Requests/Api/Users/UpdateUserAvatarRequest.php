<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateUserAvatarRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
            'avatar_original' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:51200',
        ];
    }

    public function messages(): array
    {
        return [
            'avatar.required' => __('Avatar is required'),
            'avatar.image' => __('File must be an image'),
            'avatar.mimes' => __('Avatar must be a file of type: jpeg, png, jpg, gif, webp'),
            'avatar.max' => __('Avatar may not be greater than 50MB'),
            'avatar_original.image' => __('Original avatar file must be an image'),
            'avatar_original.mimes' => __('Original avatar must be a file of type: jpeg, png, jpg, gif, webp'),
            'avatar_original.max' => __('Original avatar may not be greater than 50MB'),
        ];
    }
}
