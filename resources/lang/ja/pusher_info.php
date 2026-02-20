<?php

return [
    'validation' => [
        'page' => ['integer' => 'ページは整数である必要があります。', 'min' => 'ページは1以上である必要があります。'],
        'limit' => ['integer' => '制限は整数である必要があります。', 'min' => '制限は1以上である必要があります。'],
        'search' => ['string' => '検索は文字列である必要があります。', 'max' => '検索は255文字以下である必要があります。'],
        'sort' => ['in' => '選択されたソートフィールドは無効です。'],
        'direction' => ['in' => '方向はASCまたはDESCである必要があります。'],

        'id' => [
            'required' => 'IDは必須です。',
            'integer' => 'IDは整数である必要があります。',
            'exists' => '選択されたIDは無効です。',
        ],

        'push_type' => [
            'required' => 'プッシュタイプは必須です。',
            'in' => '選択されたプッシュタイプは無効です。',
        ],

        'pusher_app_id' => ['required' => 'Pusher App ID は必須です。'],
        'pusher_app_key' => ['required' => 'Pusher App Key は必須です。'],
        'pusher_app_secret' => ['required' => 'Pusher App Secret は必須です。'],
        'pusher_app_cluster' => ['required' => 'Pusher App Cluster は必須です。'],

        'firebase_project_id' => ['required' => 'Firebase Project ID は必須です。'],
        'firebase_credentials_path' => [
            'required' => 'Firebase 認証ファイル(json)は必須です。',
            'mimes' => 'Firebase 認証ファイルは json である必要があります。',
        ],
    ],

    'list' => [
        'message' => 'プッシュ通知設定一覧',
    ],
    'find' => [
        'message' => 'プッシュ通知設定詳細',
    ],
    'create' => [
        'success' => '設定の作成に成功しました。',
        'failed' => '設定の作成に失敗しました。',
    ],
    'update' => [
        'success' => '設定の更新に成功しました。',
        'failed' => '設定の更新に失敗しました。',
    ],
    'delete' => [
        'success' => '設定の削除に成功しました。',
        'failed' => '設定の削除に失敗しました。',
    ],
];
