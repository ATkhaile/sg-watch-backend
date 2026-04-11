<?php

namespace App\Http\Requests\Api\Post;

use App\Http\Requests\Api\ApiFormRequest;

class UpdatePostRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'link' => ['nullable', 'string', 'max:2048'],
            'media' => ['nullable', 'file', 'mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,webm', 'max:102400'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
