<?php

return [
    'list' => [
        'message' => 'Firebase通知が正常に取得されました',
    ],
    'update_readed' => [
        'success' => '通知が正常に既読としてマークされました',
        'failed' => '通知を既読としてマークできませんでした',
    ],
    'unread' => [
        'message' => '未読通知が正常に取得されました',
    ],
    'validation' => [
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは1以上である必要があります',
        ],
        'per_page' => [
            'integer' => '制限は整数である必要があります',
            'min' => '制限は1以上である必要があります',
        ],
        'fcm_token' => [
            'required' => 'FCMトークンは必須です',
            'string' => 'FCMトークンは文字列である必要があります',
            'max' => 'FCMトークンは255文字を超えることはできません',
            'exists' => 'FCMトークンが存在しません',
        ],
        'notification_id' => [
            'required' => '通知IDは必須です',
            'integer' => '通知IDは整数である必要があります',
            'exists' => '通知が存在しません',
        ],
    ],
];
