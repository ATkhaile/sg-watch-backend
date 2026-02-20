<?php

return [
    'list' => [
        'message' => 'Pusher通知一覧が正常に取得されました',
    ],
    'unread' => [
        'message' => 'Pusher未読通知が正常に取得されました',
    ],
    'update_readed' => [
        'success' => 'Pusher通知が正常に既読にされました',
        'failed' => 'Pusher通知の既読化に失敗しました',
    ],
    'validation' => [
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは1以上である必要があります',
        ],
        'limit' => [
            'integer' => '件数は整数である必要があります',
            'min' => '件数は1以上である必要があります',
            'max' => '件数は100以下である必要があります',
        ],
        'id' => [
            'required' => 'IDは必須です',
            'integer' => 'IDは整数である必要があります',
            'exists' => '通知が見つかりません',
        ],
    ],
];
