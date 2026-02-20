<?php

return [
    'create' => [
        'success' => 'Dify 作成成功',
        'failed' => 'Dify 作成失敗'
    ],
    'update' => [
        'success' => 'Dify 更新成功',
        'failed' => 'Dify 更新失敗'
    ],
    'delete' => [
        'success' => 'Dify 削除成功',
        'failed' => 'Dify 削除失敗'
    ],
    'list' => [
        'message' => 'Dify 一覧',
    ],
    'find' => [
       'message' => 'Dify 詳細',
    ],
    'not_found' => 'Dify が見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'IDは整数である必要があります',
           'exists' => 'ID が存在しません'
        ],
        'user_id' => [
            'required' => '顧客名 顧客メール が必要です',
            'integer' => '顧客名 顧客メール は整数である必要があります',
            'exists' => '顧客名 顧客メール が存在しません',
            'unique' => '顧客名 顧客メール は一意である必要があります',
        ],
        'base_url' => [
            'required' => 'base_url が必要です',
            'string' => 'base_url は文字列である必要があります',
        ],
        'app_key' => [
            'required' => 'app_key が必要です',
            'string' => 'app_key は文字列である必要があります',
        ],
        'search_user_id' => [
            'max' => '検索 user_id は256文字を超えることはできません'
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
