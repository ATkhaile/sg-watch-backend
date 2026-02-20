<?php

return [
    'create' => [
        'success' => 'MySnsBot 作成成功',
        'failed' => 'MySnsBot 作成失敗'
    ],
    'update' => [
        'success' => 'MySnsBot 更新成功',
        'failed' => 'MySnsBot 更新失敗'
    ],
    'delete' => [
        'success' => 'MySnsBot 削除成功',
        'failed' => 'MySnsBot 削除失敗'
    ],
    'list' => [
        'message' => 'MySnsBot 一覧',
    ],
    'find' => [
       'message' => 'MySnsBot 詳細',
    ],
    'not_found' => 'MySnsBot が見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'IDは整数である必要があります',
           'exists' => 'ID が存在しません'
        ],
        'my_sns_id' => [
            'required' => 'MySnsBot が必要です',
            'integer' => 'MySnsBot は整数である必要があります',
            'exists' => 'MySnsBot が存在しません',
        ],
        'channel_id' => [
            'required' => 'Channel ID が必要です',
            'string' => 'Channel ID は文字列である必要があります',
        ],
        'channel_secret' => [
            'required' => 'Channel secret が必要です',
            'string' => 'Channel secret は文字列である必要があります',
        ],
        'channel_access_token' => [
            'required' => 'Channel access token が必要です',
            'string' => 'Channel access token は文字列である必要があります',
        ],
        'search_my_sns_id' => [
            'max' => '検索 my_sns_id は256文字を超えることはできません'
        ],
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは1以上である必要があります'
        ],
        'limit' => [
            'integer' => '制限は整数でなければなりません',
            'min' => '制限は1以上である必要があります'
        ],
        'sort_name' => [
            'in' => 'Sort nameがASCまたはDESCである必要があります'
        ],
        'sort_created' => [
            'in' => 'Sort createdがASCまたはDESCである必要があります'
        ],
        'sort_updated' => [
            'in' => 'Sort updatedがASCまたはDESCである必要があります'
        ]
    ]
];
