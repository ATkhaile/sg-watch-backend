<?php

return [
    'create' => [
        'success' => 'カテゴリー作成成功',
        'failed' => 'カテゴリー作成失敗'
    ],
    'update' => [
        'success' => 'カテゴリー更新成功',
        'failed' => 'カテゴリー更新失敗'
    ],
    'delete' => [
        'success' => 'カテゴリー削除成功',
        'failed' => 'カテゴリー削除失敗'
    ],
    'list' => [
        'message' => 'カテゴリー一覧',
    ],
    'find' => [
       'message' => 'カテゴリー詳細',
    ],
    'not_found' => 'カテゴリーが見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'Idが整数である必要があります',
           'exists' => 'Idが存在しません'
        ],
        'name' => [
            'required' => 'カテゴリー名が必要です',
            'string' => 'カテゴリー名は文字列である必要があります',
            'max' => 'カテゴリー名は255文字を超えることはできません'
        ],
        'description' => [
           'string' => '説明は文字列である必要があります',
        ],
        'search_name' => [
            'max' => 'カテゴリー名は255文字を超えることはできません'
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
