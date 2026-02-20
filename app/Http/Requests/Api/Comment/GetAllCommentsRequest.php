<?php

namespace App\Http\Requests\Api\Comment;

use App\Http\Requests\Api\ApiFormRequest;

class GetAllCommentsRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;
    public function rules(): array
    {
        return [
            'model' => 'nullable|string|in:columns,news',
            'model_id' => 'nullable|integer|min:1',
            'search_content' => 'nullable|string|max:255',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
            'sort_created' => 'nullable|in:ASC,DESC',
            'sort_updated' => 'nullable|in:ASC,DESC',
        ];
    }

    public function messages(): array
    {
        return [
            'model.in' => 'Model must be either columns or news',
            'model_id.integer' => 'Model ID must be an integer',
            'model_id.min' => 'Model ID must be at least 1',
            'page.integer' => 'Page must be an integer',
            'page.min' => 'Page must be at least 1',
            'limit.integer' => 'Limit must be an integer',
            'limit.min' => 'Limit must be at least 1',
            'sort_created.in' => 'Sort created must be either ASC or DESC',
            'sort_updated.in' => 'Sort updated must be either ASC or DESC',
        ];
    }
}
