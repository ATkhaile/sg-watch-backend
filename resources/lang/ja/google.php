<?php

return [
    'callback' => [
        'success' => 'ログインに成功しました。',
        'failed' => 'エラーが発生しました。',
        'invalid_code' => '無効な認証コードです',
        'token_failed' => 'Googleからアクセストークンの取得に失敗しました',
        'user_info_failed' => 'Googleからユーザー情報の取得に失敗しました',
    ],
    'validation' => [
        'code' => [
            'required' => '認証コードが必要です',
            'string' => '認証コードは文字列である必要があります',
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
