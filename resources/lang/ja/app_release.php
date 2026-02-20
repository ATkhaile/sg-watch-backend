<?php

return [
    'validation' => [
        'name' => [
            'required' => '名前は必須です。',
            'string'   => '名前は文字列である必要があります。',
            'max'      => '名前は255文字以下である必要があります。',
        ],
        'mode' => [
            'required' => 'モードは必須です。',
            'string'   => 'モードは文字列である必要があります。',
            'in'       => '選択されたモードは無効です。',
        ],
        'required_update_flag' => [
            'required' => '必須アップデートフラグは必須です。',
            'boolean'  => '必須アップデートフラグは真偽値である必要があります。',
        ],
        'app_store_link' => [
            'string' => 'App Storeリンクは文字列である必要があります。',
            'max'    => 'App Storeリンクは500文字以下である必要があります。',
            'url'    => 'App Storeリンクの形式が無効です。',
        ],
        'chplay_store_link' => [
            'string' => 'CH Playリンクは文字列である必要があります。',
            'max'    => 'CH Playリンクは500文字以下である必要があります。',
            'url'    => 'CH Playリンクの形式が無効です。',
        ],
        'app_versions' => [
            'required' => 'バージョン情報は必須です。',
            'array'    => 'バージョン情報は配列である必要があります。',
        ],
        'version_name' => [
            'required' => 'バージョン名は必須です。',
            'string'   => 'バージョン名は文字列である必要があります。',
            'max'      => 'バージョン名は50文字以下である必要があります。',
            'exists'   => '指定されたバージョン名は存在しません。',
        ],
        'release_date' => [
            'required'    => 'リリース日は必須です。',
            'date_format' => 'リリース日はY/m/d形式で入力してください。',
        ],
        'min_version_flag' => [
            'required' => '最小バージョンフラグは必須です。',
            'boolean'  => '最小バージョンフラグは真偽値である必要があります。',
        ],
    ],
    'get' => [
        'message' => 'アプリリリース情報',
    ],
    'save' => [
        'success' => 'アプリリリースを更新しました。',
        'failed'  => 'アプリリリースの更新に失敗しました。',
    ],
    'info' => [
        'message' => 'アプリリリース情報の取得に成功しました。',
    ],
];
