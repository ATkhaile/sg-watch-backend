<?php

return [
    'create' => [
        'success' => 'ニュースが正常に作成されました',
        'failed' => 'ニュースの作成に失敗しました'
    ],
    'update' => [
        'success' => 'ニュースが正常に更新されました',
        'failed' => 'ニュースの更新に失敗しました'
    ],
    'delete' => [
        'success' => 'ニュースが正常に削除されました',
        'failed' => 'ニュースの削除に失敗しました'
    ],
    'list' => [
        'message' => 'お知らせ一覧',
    ],
    'detail' => [
        'message' => 'ニュース詳細',
    ],
    'import' => [
        'success' => 'ニュースが正常にインポートされました',
        'failed' => 'ニュースのインポートに失敗しました',
        'job_created' => 'ファイルのアップロードが完了しました。インポート処理を待機中です'
    ],

    'not_found' => 'ニュースが見つかりません',
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
            'min' => 'ページは1以上で入力してください',
        ],
        'limit' => [
            'integer' => '件数は整数で入力してください',
            'min' => '件数は1以上で入力してください',
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
        'import' => [
            'template_id_invalid' => 'テンプレートIDは正の整数で入力してください',
            'title_required' => 'タイトルは文字列で入力してください',
            'title_string' => 'タイトルは文字列で入力してください',
            'title_max' => 'タイトルは255文字以内で入力してください',
            'body_required' => '内容は文字列で入力してください',
            'body_string' => '本文は文字列で入力してください',
            'body_max' => '本文は10000文字以内で入力してください',
            'status_required' => 'ステータスは必須です',
            'status_invalid' => 'ステータスは1または2で入力してください',
            'news_not_found' => 'ニュースが見つからないか削除されています',
            'category_string' => 'カテゴリーは文字列で入力してください',
            'category_max' => 'カテゴリーは255文字以内で入力してください',
            'tag_string' => 'タグは文字列で入力してください',
            'tag_max' => 'タグは255文字以内で入力してください',
            'empty_line' => '行を完全に空にすることはできません',
            'category_create_failed' => 'カテゴリーの作成に失敗しました',
            'tag_create_failed' => 'タグの作成に失敗しました',
        ],
        'description' => [
            'required' => '説明は必須です',
            'string'   => '説明は文字列で入力してください',
            'max'      => '説明は10000文字以内で入力してください',
        ],
        'publish_date' => [
            'required'    => '公開日は必須です',
            'date_format' => '公開日はY/m/d形式で入力してください',
        ],
        'publish_flag' => [
            'required' => '公開フラグは必須です',
            'boolean'  => '公開フラグは真偽値で入力してください',
        ],
    ]
];
