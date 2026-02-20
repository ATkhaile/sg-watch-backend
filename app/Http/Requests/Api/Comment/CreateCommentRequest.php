<?php

namespace App\Http\Requests\Api\Comment;

use App\Http\Requests\Api\ApiFormRequest;

class CreateCommentRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;
    public function rules(): array
    {
        return [
            'model' => 'required|string|in:columns,news',
            'model_id' => 'required|integer|min:1',
            'content' => 'required|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'model.required' => 'Model is required',
            'model.in' => 'Model must be either columns or news',
            'model_id.required' => 'Model ID is required',
            'model_id.integer' => 'Model ID must be an integer',
            'model_id.min' => 'Model ID must be at least 1',
            'content.required' => 'Content is required',
            'content.string' => 'Content must be a string',
            'content.max' => 'Content must not exceed 5000 characters',
        ];
    }
}
