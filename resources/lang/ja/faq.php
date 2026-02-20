<?php

return [
    'create' => [
        'success' => 'FAQが正常に作成されました',
        'failed' => 'FAQの作成に失敗しました'
    ],
    'update' => [
        'success' => 'FAQが正常に更新されました',
        'failed' => 'FAQの更新に失敗しました'
    ],
    'delete' => [
        'success' => 'FAQが正常に削除されました',
        'failed' => 'FAQの削除に失敗しました'
    ],
    'list' => [
        'message' => 'FAQ一覧',
    ],
    'find' => [
       'message' => 'FAQ詳細',
    ],
    'not_found' => 'FAQが見つかりません',
    'validation' => [
        'question' => [
            'required' => '質問は必須です',
            'string' => '質問は文字列で入力してください',
            'max' => '質問は500文字以内で入力してください'
        ],
        'answer' => [
            'required' => '回答は必須です',
            'string' => '回答は文字列で入力してください'
        ],
        'display_order' => [
            'integer' => '表示順は整数で入力してください',
            'min' => '表示順は0以上で入力してください'
        ],
        'is_published' => [
            'boolean' => '公開状態は真偽値で入力してください'
        ],
        'search_question' => [
            'string' => '検索質問は文字列で入力してください'
        ],
        'search_answer' => [
            'string' => '検索回答は文字列で入力してください'
        ],
        'filter_published' => [
            'boolean' => '公開フィルターは真偽値で入力してください'
        ],
        'page' => [
            'integer' => 'ページは整数で入力してください',
            'min' => 'ページは1以上で入力してください'
        ],
        'limit' => [
            'integer' => '件数は整数で入力してください',
            'min' => '件数は1以上で入力してください',
            'max' => '件数は100以下で入力してください'
        ],
        'sort_display_order' => [
            'in' => '表示順はascまたはdescで入力してください'
        ],
        'sort_created' => [
            'in' => '作成日はascまたはdescで入力してください'
        ],
        'sort_updated' => [
            'in' => '更新日はascまたはdescで入力してください'
        ],
    ]
];
