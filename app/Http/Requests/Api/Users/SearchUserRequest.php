<?php

namespace App\Http\Requests\Api\Users;

use App\Http\Requests\Api\ApiFormRequest;

class SearchUserRequest extends ApiFormRequest
{
  public function rules(): array
  {
    return [
      'page' => 'nullable|integer|min:1',
      'limit' => 'nullable|integer|min:1',
      'keyword' => 'nullable|string|max:255',
    ];
  }

  public function messages(): array
  {
    return [
      'page.integer' => __('user.validation.page.integer'),
      'page.min' => __('user.validation.page.min'),
      'limit.integer' => __('user.validation.limit.integer'),
      'limit.min' => __('user.validation.limit.min'),
      'keyword.string' => __('user.validation.search.string'),
      'keyword.max' => __('user.validation.search.max'),
    ];
  }
}
