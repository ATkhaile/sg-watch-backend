<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ChatTypingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'receiver_id.required' => __('chat.validation.receiver_id.required'),
            'receiver_id.integer' => __('chat.validation.receiver_id.integer'),
            'receiver_id.exists' => __('chat.validation.receiver_id.exists'),
        ];
    }
}
