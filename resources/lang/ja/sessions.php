<?php

return [
    'create' => [
        'success' => 'セッションが正常に作成されました',
        'failed' => 'セッションの作成に失敗しました'
    ],
    'update' => [
        'success' => 'セッションが正常に更新されました',
        'failed' => 'セッションの更新に失敗しました'
    ],
    'delete' => [
        'success' => 'セッションが正常に削除されました',
        'failed' => 'セッションの削除に失敗しました'
    ],
    'list' => [
        'message' => 'セッション一覧',
    ],
    'find' => [
       'message' => 'セッション詳細',
    ],
    'not_found' => 'セッションが見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDは必須です',
           'integer' => 'IDは整数で入力してください',
           'exists' => 'IDは存在しません'
        ],
        'category_id' => [
            'exists' => 'カテゴリーは存在しません'
        ],
        'search_category' => [
            'max' => 'カテゴリーは255文字以内で入力してください'
        ],
        'page' => [
            'integer' => 'ページ番号は整数である必要があります',
            'min' => 'ページ番号は0より大きい必要があります'
        ],
        'limit' => [
            'integer' => '表示件数は整数である必要があります',
            'min' => '表示件数は0より大きい必要があります'
        ],
        'sort_created' => [
            'in' => '作成日の並び替え方向が無効です'
        ],
        'sort_updated' => [
            'in' => '更新日の並び替え方向が無効です'
        ],
        'user_id' => [
            'integer' => 'ユーザーIDは整数である必要があります',
            'exists' => 'ユーザーが存在しません'
        ]
    ]
];
