<?php

return [
    'create' => [
        'success' => 'メッセージ作成成功',
        'failed' => 'メッセージ作成失敗'
    ],
    'reply' => [
        'success' => 'メッセージ返信成功',
        'failed' => 'メッセージ返信失敗'
    ],
    'list' => [
        'message' => 'メッセージ一覧',
    ],
    'not_found' => 'メッセージが見つかりません',
    'invalid' => '無効なチャットモード',
    'validation' => [
        'email' => [
           'required' => 'emailが必要です',
           'email' => 'emailが正しくありません',
        ],
        'name' => [
           'required' => '名前が必要です',
           'string' => 'emailが正しくありません',
        ],
        'code' => [
           'required' => 'コードが必要です',
           'string' => 'コードは文字列である必要があります',
           'size' => 'コードは8文字でなければなりません',
           'not_exists' => 'コードは存在しません',
        ],
        'message' => [
           'required' => 'メッセージが必要です',
           'string' => 'メッセージは文字列である必要があります',
        ],
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは1以上である必要があります'
        ],
        'limit' => [
            'integer' => '制限は整数でなければなりません',
            'min' => '制限は1以上である必要があります'
        ],
    ],
    'login' => [
        'success' => 'ログイン成功',
        'failed' => 'ログイン失敗',
    ],
    'session' => [
        'success' => 'チャットルームが正常に作成され、コードが電子メールで送信されました。',
        'exists' => 'このメールのチャット ルームは既に存在します。',
        'error' => 'チャットルームの作成に失敗しました',
    ],
];
