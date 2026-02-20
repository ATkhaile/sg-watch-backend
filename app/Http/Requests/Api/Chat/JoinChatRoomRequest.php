<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class JoinChatRoomRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'receiver_id' => 'required|integer|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => __('chat.validation.user_id.required'),
            'user_id.integer' => __('chat.validation.user_id.integer'),
            'user_id.exists' => __('chat.validation.user_id.exists'),
            'receiver_id.required' => __('chat.validation.receiver_id.required'),
            'receiver_id.integer' => __('chat.validation.receiver_id.integer'),
            'receiver_id.exists' => __('chat.validation.receiver_id.exists'),
        ];
    }
}
