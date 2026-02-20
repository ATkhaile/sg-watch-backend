<?php

namespace App\Http\Requests\Api\NotificationPush;

use App\Enums\IOSSystemSound;
use App\Enums\RedirectType;
use App\Http\Requests\Api\ApiFormRequest;
use App\Models\AppPage;
use App\Models\User;
use Illuminate\Validation\Rule;

class CreateNotificationPushRequest extends ApiFormRequest
{
    use \App\Http\Requests\Traits\AuthorizationTrait;

    public function rules(): array
    {
        $redirectType = $this->input('redirect_type');

        $attachFileRule = ['nullable', 'file', 'max:307200'];
        if ($redirectType === RedirectType::IMAGE) {
            $attachFileRule[] = 'mimes:jpeg,png,jpg,gif,svg';
        } elseif ($redirectType === RedirectType::VIDEO) {
            $attachFileRule[] = 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska';
        }
        return [
            'title'   => 'required|string|max:255',
            'message' => 'required|string',
            'img_path' => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:307200',
            'all_user_flag' => 'required|boolean',
            'push_now_flag' => 'required|boolean',
            'sound' => [
                'nullable',
                'string',
                Rule::in(IOSSystemSound::getStringValues()),
            ],
            'push_schedule' => 'nullable|date|required_if:push_now_flag,false',
            'user_ids' => [
                'nullable',
                'array',
                'required_if:all_user_flag,false',
            ],
            'user_ids.*' => [
                'integer',
                Rule::exists(User::class, 'id')->whereNull('deleted_at'),
            ],
            'redirect_type' => [
                'nullable',
                'string',
                Rule::in(RedirectType::getStringValues()),
            ],
            'app_page_id' => [
                'nullable',
                'integer',
                'required_if:redirect_type,app_page',
                Rule::exists(AppPage::class, 'id')->whereNull('deleted_at'),
            ],
            'attach_file' => $attachFileRule,
            'attach_link' => [
                'nullable',
                'string',
                'max:2048',
                'required_if:redirect_type,webview',
                'url',
            ],

        ];
    }

    public function messages(): array
    {
        return [
            'title.required'   => __('notification_push.validation.title.required'),
            'title.string'     => __('notification_push.validation.title.string'),
            'title.max'        => __('notification_push.validation.title.max'),
            'message.required' => __('notification_push.validation.message.required'),
            'message.string'   => __('notification_push.validation.message.string'),

            'img_path.mimes' => __('notification_push.validation.img_path.mimes'),
            'img_path.max'   => __('notification_push.validation.img_path.max'),

            'all_user_flag.required' => __('notification_push.validation.all_user_flag.required'),
            'all_user_flag.boolean'  => __('notification_push.validation.all_user_flag.boolean'),

            'push_now_flag.required' => __('notification_push.validation.push_now_flag.required'),
            'push_now_flag.boolean'  => __('notification_push.validation.push_now_flag.boolean'),

            'sound.string'  => __('notification_push.validation.sound.string'),
            'sound.in'    => __('notification_push.validation.sound.in'),

            'push_schedule.date'        => __('notification_push.validation.push_schedule.date'),
            'push_schedule.required_if' => __('notification_push.validation.push_schedule.required_if'),

            'user_ids.array'            => __('notification_push.validation.user_ids.array'),
            'user_ids.required_if'      => __('notification_push.validation.user_ids.required_if'),
            'user_ids.*.integer'        => __('notification_push.validation.user_ids.*.integer'),
            'user_ids.*.exists'         => __('notification_push.validation.user_ids.*.exists'),
            'redirect_type.in' => __('notification_push.validation.redirect_type.in'),
            'app_page_id.required_if' => __('notification_push.validation.app_page_id.required_if'),
            'app_page_id.integer' => __('notification_push.validation.app_page_id.integer'),
            'app_page_id.exists' => __('notification_push.validation.app_page_id.exists'),
            'attach_file.file' => __('notification_push.validation.attach_file.file'),
            'attach_file.max' => __('notification_push.validation.attach_file.max'),
            'attach_file.mimes' => __('notification_push.validation.attach_file.mimes'),
            'attach_file.mimetypes' => __('notification_push.validation.attach_file.mimetypes'),
            'attach_link.required_if' => __('notification_push.validation.attach_link.required_if'),
            'attach_link.url' => __('notification_push.validation.attach_link.url'),
            'attach_link.max' => __('notification_push.validation.attach_link.max'),

        ];
    }
}
