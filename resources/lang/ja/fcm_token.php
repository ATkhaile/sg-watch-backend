<?php

return [
    'validation' => [
        'fcm_token' => [
            'required' => 'FCMトークンは必須です。',
            'string' => 'FCMトークンは文字列である必要があります。',
            'max' => 'FCMトークンは255文字以下である必要があります。',
        ],
        'user_id' => [
            'required' => 'ユーザーIDは必須です。',
            'integer'  => 'ユーザーIDは整数でなければなりません。',
            'exists'   => 'ユーザーが存在しません。',
        ],
        'fcm_token_id' => [
            'required' => 'FCMトークンIDは必須です。',
            'integer'  => 'FCMトークンIDは整数でなければなりません。',
            'exists'   => 'FCMトークンが存在しません。',
        ],
        'active_status' => [
            'required' => 'ステータスは必須です。',
            'string'   => 'ステータスは文字列でなければなりません。',
            'in'       => 'ステータスはactiveまたはdeactiveでなければなりません。',
        ],
        'device_name' => [
            'string' => 'デバイス名は文字列である必要があります。',
            'max'    => 'デバイス名は255文字以下である必要があります。',
        ],
        'app_version_name' => [
            'string' => 'アプリバージョン名は文字列である必要があります。',
            'exists' => '指定されたアプリバージョンが存在しません。',
        ],
        'app_id' => [
            'string' => 'アプリIDは文字列である必要があります。',
            'max' => 'アプリIDは255文字以下である必要があります。',
        ],
    ],
    'create' => [
        'successful' => 'FCMトークンが正常に作成されました。',
        'failed' => 'FCMトークンの作成に失敗しました。',
    ],
    'list' => [
        'message' => 'デバイス一覧の取得に成功しました。',
    ],
    'update_status' => [
        'success' => 'FCMトークンのステータス更新に成功しました。',
        'failed'  => 'FCMトークンのステータス更新に失敗しました。',
    ],
    'delete' => [
        'successful' => 'FCM トークンが正常に削除されました。',
        'failed' => 'FCM トークンの削除に失敗しました。',
    ],
];
