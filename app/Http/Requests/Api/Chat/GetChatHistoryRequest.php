<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class GetChatHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => 'required|integer|exists:users,id',
            'page' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id' => __('chat.validation.receiver_id.required'),
            'receiver_id.integer' => __('chat.validation.receiver_id.integer'),
            'page.integer' => __('chat.validation.page.integer'),
            'page.min' => __('chat.validation.page.min'),
            'limit.integer' => __('chat.validation.limit.integer'),
            'limit.min' => __('chat.validation.limit.min'),
        ];
    }
}
