<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class GetAvailableUsersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1',
            'message_search' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'search.string' => __('chat.validation.search.string'),
            'search.max' => __('chat.validation.search.max'),
            'limit.integer' => __('chat.validation.limit.integer'),
            'limit.min' => __('chat.validation.limit.min'),
            'limit.max' => __('chat.validation.limit.max'),
            'page.integer' => __('chat.validation.page.integer'),
            'page.min' => __('chat.validation.page.min'),
            'message_search.string' => __('chat.validation.message_search.string'),
            'message_search.max' => __('chat.validation.message_search.max'),
        ];
    }
}
