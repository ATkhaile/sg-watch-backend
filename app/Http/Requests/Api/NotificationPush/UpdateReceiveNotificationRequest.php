<?php

namespace App\Http\Requests\Api\NotificationPush;

use App\Http\Requests\Api\ApiFormRequest;
use App\Models\NotificationPush;
use Illuminate\Validation\Rule;

class UpdateReceiveNotificationRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;
    public function rules(): array
    {
        return [
            'fcm_token' => 'required|string|max:255',
            'receive_notification_chat' => 'nullable|boolean',
            'receive_reservation' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'fcm_token.required' => __('notification_push.validation.fcm_token.required'),
            'fcm_token.string'   => __('notification_push.validation.fcm_token.string'),
            'fcm_token.max'      => __('notification_push.validation.fcm_token.max'),

            'receive_notification_chat.boolean'  => __('notification_push.validation.receive_notification_chat.boolean'),
            'receive_reservation.boolean'  => __('notification_push.validation.receive_reservation.boolean'),
        ];
    }
}
