<?php

return [
    'create' => [
        'success' => 'MySns 作成成功',
        'failed' => 'MySns 作成失敗'
    ],
    'update' => [
        'success' => 'MySns 更新成功',
        'failed' => 'MySns 更新失敗'
    ],
    'delete' => [
        'success' => 'MySns 削除成功',
        'failed' => 'MySns 削除失敗'
    ],
    'list' => [
        'message' => 'MySns 一覧',
    ],
    'find' => [
       'message' => 'MySns 詳細',
    ],
    'user_id' => [
        'required' => 'Dify が必要です',
        'integer' => 'Dify は整数である必要があります',
        'exists' => 'Dify が存在しません',
    ],
    'not_found' => 'MySns が見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'IDは整数である必要があります',
           'exists' => 'ID が存在しません'
        ],
        'email' => [
            'required' => 'Email が必要です',
            'email' => 'Email は正しい形式である必要があります',
        ],
        'password' => [
            'required' => 'password が必要です',
            'string' => 'password は文字列である必要があります',
        ],
        'service_name' => [
            'required' => 'Service name が必要です',
            'string' => 'Service name は文字列である必要があります',
        ],
        'service_description' => [
            'required' => 'Service description が必要です',
            'string' => 'Service description は文字列である必要があります',
        ],
        'usage_description' => [
            'required' => 'Usage description が必要です',
            'string' => 'Usage description は文字列である必要があります',
        ],
        'pricing_plan' => [
            'required' => 'Pricing plan が必要です',
            'string' => 'Pricing plan は文字列である必要があります',
        ],
        'usage_limit' => [
            'required' => 'Usage limit が必要です',
            'string' => 'Usage limit は文字列である必要があります',
        ],
        'supported_env' => [
            'required' => 'Supported environment が必要です',
            'string' => 'Supported environment は文字列である必要があります',
        ],
        'faq' => [
            'required' => 'Faq が必要です',
            'string' => 'Faq は文字列である必要があります',
        ],
        'search_name' => [
            'max' => 'プラン名は255文字を超えることはできません'
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
