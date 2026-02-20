<?php

return [
    'callback' => [
        'success' => 'ログインに成功しました。',
        'failed' => 'エラーが発生しました。',
        'user_info_failed' => 'Facebookからユーザー情報の取得に失敗しました',
    ],
    'validation' => [
        'token' => [
            'required' => 'トークンが必要です',
            'string' => 'トークンは文字列である必要があります',
        ],
        'type' => [
            'in' => '選択されたタイプは無効です',
            'string' => 'タイプは文字列である必要があります',
        ],
    ],
];