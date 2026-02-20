<?php

namespace App\Http\Requests\Api\Notifications;

use App\Enums\PushType;
use App\Enums\SenderType;
use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;

class CreateNotificationsRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:10000',
            'push_type' => [
                'required',
                'string',
                'max:50',
                Rule::in(PushType::getValues()),
            ],
            'push_datetime' => 'nullable|date_format:Y/m/d H:i:s',
            'push_now_flag' => 'nullable|boolean',
            'image_file' => 'nullable|mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp|max:10240', // 10MB
            'sender_type' => [
                'required',
                'string',
                'max:50',
                Rule::in(SenderType::getValues()),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => __('notifications.validation.title.required'),
            'title.string' => __('notifications.validation.title.string'),
            'title.max' => __('notifications.validation.title.max'),
            'content.required' => __('notifications.validation.content.required'),
            'content.string' => __('notifications.validation.content.string'),
            'content.max' => __('notifications.validation.content.max'),
            'push_type.string' => __('notifications.validation.type.string'),
            'push_type.max' => __('notifications.validation.type.max'),
            'push_type.in' => __('notifications.validation.type.in'),
            'push_type.required' => __('notifications.validation.type.required'),
            'push_datetime.date_format' => __('notifications.validation.push_datetime.date_format'),
            'push_now_flag.boolean' => __('notifications.validation.push_now_flag.boolean'),
            'image_file.mimetypes' => __('notifications.validation.image_file.image'),
            'image_file.max' => __('notifications.validation.image_file.max'),
            'sender_type.required' => __('notifications.validation.sender_type.required'),
            'sender_type.string' => __('notifications.validation.sender_type.string'),
            'sender_type.max' => __('notifications.validation.sender_type.max'),
            'sender_type.in' => __('notifications.validation.sender_type.in'),
        ];
    }
}
