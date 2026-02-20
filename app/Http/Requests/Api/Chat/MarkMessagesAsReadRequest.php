<?php

namespace App\Http\Requests\Api\Chat;

use Illuminate\Foundation\Http\FormRequest;

class MarkMessagesAsReadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'chat_partner_id' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value == auth()->id()) {
                        $fail(__('chat.validation.user.cannot_mark_own_messages'));
                    }
                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'chat_partner_id.required' => __('chat.validation.user_id.required'),
            'chat_partner_id.integer' => __('chat.validation.user_id.integer'),
            'chat_partner_id.exists' => __('chat.validation.user_id.not_found'),
        ];
    }

    public function attributes(): array
    {
        return [
            'chat_partner_id' => __('chat.attributes.chat_partner'),
        ];
    }
}