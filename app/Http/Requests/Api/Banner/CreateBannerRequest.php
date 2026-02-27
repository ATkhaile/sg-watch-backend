<?php

namespace App\Http\Requests\Api\Banner;

use App\Http\Requests\Api\ApiFormRequest;

class CreateBannerRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'media' => ['required', 'file', 'mimes:jpeg,png,jpg,gif,webp,mp4,mov,avi,wmv,webm', 'max:102400'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
