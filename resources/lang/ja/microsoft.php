<?php

return [
    'callback' => [
        'success' => 'ログインに成功しました。',
        'failed' => 'エラーが発生しました。',
        'token_failed' => 'Microsoftからトークンの取得に失敗しました',
        'user_info_failed' => 'Microsoftからユーザー情報の取得に失敗しました',
    ],
    'validation' => [
        'code' => [
            'required' => 'コードが必要です',
            'string' => 'コードは文字列である必要があります',
        ],
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
