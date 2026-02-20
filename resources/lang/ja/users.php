<?php

return [
    'create' => [
        'success' => 'ユーザー作成成功',
        'failed' => 'ユーザー作成失敗'
    ],
    'update' => [
        'success' => 'ユーザー更新成功',
        'failed' => 'ユーザー更新失敗'
    ],
    'delete' => [
        'success' => 'ユーザー削除成功',
        'failed' => 'ユーザー削除失敗'
    ],
    'list' => [
        'message' => 'ユーザー一覧',
    ],
    'find' => [
        'message' => 'ユーザー詳細',
    ],
    'not_found' => 'ユーザーが見つかりません',
    'options' => [
        'message' => 'ユーザーオプション一覧',
    ],
    'validation' => [
        'id' => [
            'required' => 'IDが必要です',
            'integer' => 'IDが整数である必要があります',
            'exists' => 'IDが存在しません'
        ],
        'first_name' => [
            'required' => '名が必要です',
            'string' => '名は文字列である必要があります',
            'max' => '名は50文字を超えることはできません'
        ],
        'last_name' => [
            'required' => '姓が必要です',
            'string' => '姓は文字列である必要があります',
            'max' => '姓は50文字を超えることはできません'
        ],
        'email' => [
            'required' => 'Emailが必要です',
            'unique' => 'Emailは既に存在します',
            'email' => 'Emailは有効なメールアドレスである必要があります',
        ],
        'password' => [
            'required' => 'パスワードが必要です',
            'min' => 'パスワードは8文字以上である必要があります',
            'max' => 'パスワードは255文字を超えることはできません'
        ],
        'password_confirmation' => [
            'required' => 'パスワード確認が必要です',
            'same' => 'パスワードとパスワード確認が一致しません'
        ],
        'gender' => [
            'in' => '性別はmale、female、other、unknownのいずれかである必要があります'
        ],
        'search' => [
            'max' => '検索は255文字を超えることはできません'
        ],
        'search_email' => [
            'max' => '検索メールは255文字を超えることはできません'
        ],
        'search_email_like' => [
            'boolean' => '検索メールは真偽値である必要があります'
        ],
        'search_email_not' => [
            'boolean' => '検索メールは真偽値である必要があります'
        ],
        'page' => [
            'integer' => 'ページは整数である必要があります',
            'min' => 'ページは1以上である必要があります'
        ],
        'limit' => [
            'integer' => '制限は整数でなければなりません',
            'min' => '制限は1以上である必要があります'
        ],
        'sort_first_name' => [
            'in' => 'Sort first nameがASCまたはDESCである必要があります'
        ],
        'sort_email' => [
            'in' => 'Sort emailがASCまたはDESCである必要があります'
        ],
        'sort_created' => [
            'in' => 'Sort createdがASCまたはDESCである必要があります'
        ],
        'sort_updated' => [
            'in' => 'Sort updatedがASCまたはDESCである必要があります'
        ],
        'admin' => [
            'boolean' => '管理者はtrueまたはfalseである必要があります'
        ]
    ]
];
