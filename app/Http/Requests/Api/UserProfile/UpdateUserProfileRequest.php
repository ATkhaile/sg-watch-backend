<?php

namespace App\Http\Requests\Api\UserProfile;

use App\Http\Requests\Api\ApiFormRequest;
use Illuminate\Validation\Rule;
use App\Models\Prefecture;

class UpdateUserProfileRequest extends ApiFormRequest
{
    public function rules(): array
    {
        return [
            'email_send' => 'required|max:255|email',
            'postal_code' => 'required|max:8|regex:/^([0-9]{3}-?[0-9]{4})$/',
            'prefecture_id' => [
                'required',
                'string',
                Rule::exists(Prefecture::class, 'prefecture_id')->whereNull('deleted_at')
            ],
            'city' => 'required|max:255',
            'password' => 'nullable|min:8|max:16|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9]+$/',
            'street_address' => 'required|max:255',
            'building' => 'nullable|max:255',
            'line_name' => 'required|max:255',
            'last_name_kanji' => 'required|max:255',
            'first_name_kanji' => 'required|max:255',
            'last_name_kana' => [
                'required',
                'max:255',
                'regex:/^([ァ-ンｧ-ﾝﾞﾟ]|ー|　| |（|）|\(|\))+$/u',
            ],
            'first_name_kana' => [
                'required',
                'max:255',
                'regex:/^([ァ-ンｧ-ﾝﾞﾟ]|ー|　| |（|）|\(|\))+$/u',
            ],
            'group_name' => 'nullable|max:255',
            'group_name_kana' => [
                'nullable',
                'max:255',
                'regex:/^([ァ-ンｧ-ﾝﾞﾟ]|ー|　| |（|）|\(|\))+$/u',
            ],
            'phone' => [
                'required',
                'max:16',
                'regex:/^(0(\d-\d{4}-\d{4}))|(0(\d{3}-\d{2}-\d{4}))|((070|080|090|050)(-\d{4}-\d{4}))|(0(\d{2}-\d{3}-\d{4}))|(0(\d{9,10}))+$/',
            ],
            'is_black' => 'required|bool',
            'billing_same_address_flag' => 'required|bool',
            'memo' => 'nullable|max:1000',
            'birthday' => [
                'required',
                'date_format:Y/m/d',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'email_send.required' => __('user_profile.validation.email_send.required'),
            'email_send.email' => __('user_profile.validation.email_send.email'),
            'postal_code.required' => __('user_profile.validation.postal_code.required'),
            'postal_code.regex' => __('user_profile.validation.postal_code.regex'),
            'prefecture_id.required' => __('user_profile.validation.prefecture_id.required'),
            'prefecture_id.exists' => __('user_profile.validation.prefecture_id.exists'),
            'city.required' => __('user_profile.validation.city.required'),
            'password.min' => __('user_profile.validation.password.min'),
            'password.regex' => __('user_profile.validation.password.regex'),
            'street_address.required' => __('user_profile.validation.street_address.required'),
            'line_name.required' => __('user_profile.validation.line_name.required'),
            'last_name_kanji.required' => __('user_profile.validation.last_name_kanji.required'),
            'first_name_kanji.required' => __('user_profile.validation.first_name_kanji.required'),
            'last_name_kana.required' => __('user_profile.validation.last_name_kana.required'),
            'last_name_kana.regex' => __('user_profile.validation.last_name_kana.regex'),
            'first_name_kana.required' => __('user_profile.validation.first_name_kana.required'),
            'first_name_kana.regex' => __('user_profile.validation.first_name_kana.regex'),
            'group_name_kana.regex' => __('user_profile.validation.group_name_kana.regex'),
            'phone.required' => __('user_profile.validation.phone.required'),
            'phone.regex' => __('user_profile.validation.phone.regex'),
            'is_black.required' => __('user_profile.validation.is_black.required'),
            'billing_same_address_flag.required' => __('user_profile.validation.billing_same_address_flag.required'),
            'birthday.required' => __('user_profile.validation.birthday.required'),
            'birthday.date_format' => __('user_profile.validation.birthday.date_format'),
        ];
    }
}
