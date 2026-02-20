<?php

namespace App\Http\Requests\Api\Comment;

use App\Http\Requests\Api\ApiFormRequest;

class DeleteCommentRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;
    public function rules(): array
    {
        return [];
    }
}
