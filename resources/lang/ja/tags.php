<?php

return [
    'create' => [
        'success' => 'タグ作成成功',
        'failed' => 'タグ作成失敗'
    ],
    'update' => [
        'success' => 'タグ更新成功',
        'failed' => 'タグ更新失敗'
    ],
    'delete' => [
        'success' => 'タグ削除成功',
        'failed' => 'タグ削除失敗'
    ],
    'list' => [
        'message' => 'タグ一覧',
    ],
    'find' => [
       'message' => 'タグ詳細',
    ],
    'not_found' => 'タグが見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'Idが整数である必要があります',
           'exists' => 'Idが存在しません'
        ],
        'name' => [
            'required' => 'タグ名が必要です',
            'string' => 'タグ名は文字列である必要があります',
            'max' => 'タグ名は255文字を超えることはできません'
        ],
        'search_name' => [
            'max' => 'タグ名は255文字を超えることはできません'
        ],
        'search_name_like' => [
            'boolean' => '検索名は真偽値である必要があります'
        ],
        'search_name_not' => [
            'boolean' => '検索名は真偽値である必要があります'
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
