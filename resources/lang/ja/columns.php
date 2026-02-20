<?php

return [
    'create' => [
        'success' => 'コラムが正常に作成されました',
        'failed' => 'コラムの作成に失敗しました'
    ],
    'update' => [
        'success' => 'コラムが正常に更新されました',
        'failed' => 'コラムの更新に失敗しました'
    ],
    'delete' => [
        'success' => 'コラムが正常に削除されました',
        'failed' => 'コラムの削除に失敗しました'
    ],
    'list' => [
        'message' => 'コラム一覧',
    ],
    'find' => [
       'message' => 'コラム詳細',
    ],
    'checkout' => [
        'success' => 'チェックアウトセッションが正常に作成されました',
        'failed' => 'チェックアウトセッションの作成に失敗しました'
    ],
    'not_found' => 'コラムが見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDは必須です',
           'integer' => 'IDは整数で入力してください',
           'exists' => 'IDは存在しません'
        ],
        'title' => [
            'required' => 'タイトルは必須です',
            'string' => 'タイトルは文字列で入力してください',
            'max' => 'タイトルは255文字以内で入力してください'
        ],
        'content' => [
            'required' => '内容は必須です',
            'string' => '内容は文字列で入力してください'
        ],
        'category_id' => [
            'exists' => 'カテゴリーは存在しません'
        ],
        'tag_ids' => [
            'array' => 'タグIDは配列で入力してください',
            'exists' => 'タグは存在しません'
        ],
        'search_title' => [
            'max' => 'タイトルは255文字以内で入力してください'
        ],
        'search_title_like' => [
            'boolean' => 'タイトルは真偽値で入力してください'
        ],
        'search_title_not' => [
            'boolean' => 'タイトルは真偽値で入力してください'
        ],
        'search_category' => [
            'max' => 'カテゴリーは255文字以内で入力してください'
        ],
        'search_category_like' => [
            'boolean' => 'カテゴリーは真偽値で入力してください'
        ],
        'search_category_not' => [
            'boolean' => 'カテゴリーは真偽値で入力してください'
        ],
        'search_tags' => [
            'array' => 'タグは配列で入力してください'
        ],
        'search_tags_like' => [
            'boolean' => 'タグは真偽値で入力してください'
        ],
        'search_tags_not' => [
            'boolean' => 'タグは真偽値で入力してください'
        ],
        'page' => [
            'integer' => 'ページは整数で入力してください',
            'min' => 'ページは1以上で入力してください'
        ],
        'limit' => [
            'integer' => '件数は整数で入力してください',
            'min' => '件数は1以上で入力してください'
        ],
        'sort_title' => [
            'in' => 'タイトルはASCまたはDESCで入力してください'
        ],
        'sort_category' => [
            'in' => 'カテゴリーはASCまたはDESCで入力してください'
        ],
        'sort_created' => [
            'in' => '作成日はASCまたはDESCで入力してください'
        ],
        'sort_updated' => [
            'in' => '更新日はASCまたはDESCで入力してください'
        ],
        'stripe_product_id' => [
            'string' => 'Stripe商品IDは文字列である必要があります',
            'max' => 'Stripe商品IDは255文字を超えることはできません'
        ],
        'stripe_price_id' => [
            'string' => 'Stripe価格IDは文字列である必要があります',
            'max' => 'Stripe価格IDは255文字を超えることはできません'
        ],
        'stripe_account_id' => [
            'exists' => 'Stripeアカウントが見つかりません'
        ],
        'image' => [
            'image' => '画像ファイルである必要があります',
            'mimes' => '画像はjpeg、jpg、png、gif、webp形式である必要があります',
            'max' => '画像は10MBを超えることはできません'
        ]
    ]
];
