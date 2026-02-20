<?php

return [
    'validation' => [
        'line_user_id' => [
            'required' => 'Line User IDが必要です。',
            'string' => 'Line User IDは文字列である必要があります。',
        ],
        'display_name' => [
            'required' => '表示名が必要です。',
            'string' => '表示名は文字列である必要があります。',
        ],
        'picture_url' => [
            'url' => '画像URLは有効なURLである必要があります。',
        ],
        'type' => [
            'string' => 'タイプは文字列である必要があります。',
            'in' => '選択されたタイプは無効です。',
        ],
    ],
    'login' => [
        'success' => 'Line Miniログインが成功しました。',
        'error' => 'Line Miniログイン中にエラーが発生しました。',
    ],
    'callback' => [
        'success' => 'Line Miniコールバックが正常に処理されました。',
        'error' => 'Line Miniコールバック中にエラーが発生しました。',
    ],
];