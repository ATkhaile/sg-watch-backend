<?php

namespace App\Http\Requests\Api\ShopReview;

use App\Http\Requests\Api\ApiFormRequest;

class UpdateReviewRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:2000'],
            'existing_images' => ['nullable', 'array'],
            'existing_images.*' => ['string', 'max:500'],
            'images' => ['nullable', 'array'],
            'images.*' => ['file', 'image', 'max:5120'],
        ];
    }
}
