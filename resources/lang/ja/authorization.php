<?php

return [
    'role' => [
        'create' => [
            'success' => '役割作成成功',
            'failed' => '役割作成失敗'
        ],
        'update' => [
            'success' => '役割更新成功',
            'failed' => '役割更新失敗'
        ],
        'delete' => [
            'success' => '役割削除成功',
            'failed' => '役割削除失敗',
            'system_role_cannot_be_deleted' => 'システムロールは削除できません'
        ],
        'list' => [
            'message' => '役割一覧',
        ],
        'find' => [
           'message' => '役割詳細',
        ],
        'not_found' => '役割が見つかりません',
        'validation' => [
            'id' => [
               'required' => 'IDが必要です',
               'integer' => 'Idが整数である必要があります',
               'exists' => 'Idが存在しません'
            ],
            'name' => [
                'required' => '役割名が必要です',
                'unique' => '役割名は一意である必要があります',
                'regex' => '役割名にスペースを含めることはできません',
                'string' => '役割名は文字列である必要があります',
                'max' => '役割名は255文字を超えることはできません'
            ],
            'search_name' => [
                'max' => '役割名は255文字を超えることはできません'
            ],
            'search_name_like' => [
                'boolean' => '検索名は真偽値である必要があります'
            ],
            'search_name_not' => [
                'boolean' => '検索名は真偽値である必要があります'
            ],
            'search_domain' => [
                'max' => 'ドメインは255文字を超えることはできません'
            ],
            'search_domain_like' => [
                'boolean' => '検索名は真偽値である必要があります'
            ],
            'search_domain_not' => [
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
        ],
        'attach' => [
            'success' => '役割に許可を付与しました',
            'failed' => '役割に許可を付与できませんでした'
        ],
        'detach' => [
            'success' => '役割から許可を削除しました',
            'failed' => '役割から許可を削除できませんでした'
        ],
        'role_id' => [
            'required' => '役割IDが必要です',
            'exists' => '役割IDが存在しません'
        ],
        'permission_ids' => [
            'required' => '許可IDが必要です',
            'array' => '許可IDは配列である必要があります',
            'invalid' => '許可IDが無効: :values',
            'exists' => '許可IDが存在しません'
        ],

    ],
    'permission' => [
        'create' => [
            'success' => '許可作成成功',
            'failed' => '許可作成失敗'
        ],
        'update' => [
            'success' => '許可更新成功',
            'failed' => '許可更新失敗'
        ],
        'delete' => [
            'success' => '許可削除成功',
            'failed' => '許可削除失敗'
        ],
        'list' => [
            'message' => '許可一覧',
        ],
        'find' => [
           'message' => '許可詳細',
        ],
        'not_found' => '許可が見つかりません',
        'validation' => [
            'id' => [
               'required' => 'IDが必要です',
               'integer' => 'Idが整数である必要があります',
               'exists' => 'Idが存在しません'
            ],
            'name' => [
                'required' => '許可名が必要です',
                'unique' => '許可名は一意である必要があります',
                'regex' => '許可名にスペースを含めることはできません',
                'string' => '許可名は文字列である必要があります',
                'max' => '許可名は255文字を超えることはできません'
            ],
            'search_name' => [
                'max' => '許可名は255文字を超えることはできません'
            ],
            'search_name_like' => [
                'boolean' => '検索名は真偽値である必要があります'
            ],
            'search_name_not' => [
                'boolean' => '検索名は真偽値である必要があります'
            ],
            'display_name' => [
                'required' => '表示名が必要です',
                'string' => '表示名は文字列である必要があります',
                'max' => '表示名は255文字を超えることはできません'
            ],
            'description' => [
                'string' => '説明は文字列である必要があります',
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
        ],
        'attach' => [
            'success' => '許可に役割を付与しました',
            'failed' => '許可に役割を付与できませんでした'
        ],
        'detach' => [
            'success' => '許可から役割を削除しました',
            'failed' => '許可から役割を削除できませんでした'
        ],

    ],
    'user' => [
        'user_id'  => [
            'required' => 'ユーザーIDが必要です',
            'exists' => 'ユーザーIDが存在しません'
        ],
        'permission_ids' => [
            'required' => '許可IDが必要です',
            'array' => '許可IDは配列である必要があります',
            'invalid' => '許可IDが無効: :values',
            'exists' => '許可IDが存在しません'
        ],
        'role_ids' => [
            'required' => '役割IDが必要です',
            'array' => '役割IDは配列である必要があります',
            'invalid' => '役割IDが無効: :values',
            'exists' => '役割IDが存在しません'
        ],
    ]
];
