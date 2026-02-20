<?php

return [
    'validation' => [
        'page' => [
            'integer' => 'ページは整数である必要があります。',
            'min' => 'ページは1以上である必要があります。',
        ],
        'limit' => [
            'integer' => '制限は整数である必要があります。',
            'min' => '制限は1以上である必要があります。',
        ],
        'search' => [
            'string' => '検索は文字列である必要があります。',
            'max' => '検索は255文字以下である必要があります。',
        ],
        'sort' => [
            'in' => '選択されたソートフィールドは無効です。',
        ],
        'direction' => [
            'in' => '方向はASCまたはDESCである必要があります。',
        ],
        'id' => [
            'required' => 'IDは必須です。',
            'integer' => 'IDは整数である必要があります。',
            'exists' => '選択されたIDは無効です。',
        ],
        'type' => [
            'required' => 'タイプは必須です。',
            'in' => '選択されたタイプは無効です。',
        ],
        'domain' => [
            'required' => 'Domain は必須です。',
        ],
        'app_id' => [
            'required' => 'App ID は必須です。',
        ],
        'unlimit_expires' => [
            'required' => '無期限フラグは必須です。',
            'boolean' => '無期限フラグは true/false である必要があります。',
        ],
        'expired_at' => [
            'required' => '有効期限は必須です。',
            'date' => '有効期限は日付形式である必要があります。',
        ],
        'status' => [
            'required' => 'ステータスは必須項目です。',
            'boolean'  => 'ステータスの形式が正しくありません。',
        ],
    ],

    'list' => [
        'message' => '署名情報一覧',
    ],
    'find' => [
        'message' => '署名情報詳細',
    ],
    'create' => [
        'success' => '作成に成功しました。',
        'failed' => '作成に失敗しました。',
    ],
    'update' => [
        'success' => '更新に成功しました。',
        'failed' => '更新に失敗しました。',
    ],
    'delete' => [
        'success' => '削除に成功しました。',
        'failed' => '削除に失敗しました。',
    ],
    'regenerate_secret_key' => [
        'success' => 'シークレットキーを再生成しました。',
        'failed' => 'シークレットキーの再生成に失敗しました。',
    ],
];
