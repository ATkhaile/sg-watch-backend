<?php

return [
    'list' => [
        'message' => 'AIツール一覧を取得しました',
    ],
    'not_found' => 'AIツールが見つかりません',
    'report_retrieved' => 'AIツールレポートを取得しました',
    'update' => [
        'success' => 'AIツールを更新しました',
        'failed' => 'AIツールの更新に失敗しました',
    ],
    'toggle_active' => [
        'success' => 'AIツールのステータスを変更しました',
        'failed' => 'AIツールのステータス変更に失敗しました',
    ],
    'validation' => [
        'search_name' => [
            'max' => '検索名は256文字以内で入力してください',
        ],
        'search_name_like' => [
            'boolean' => '検索名のあいまい検索は真偽値で入力してください',
        ],
        'search_active' => [
            'boolean' => 'アクティブステータスは真偽値で入力してください',
        ],
        'page' => [
            'integer' => 'ページ番号は整数で入力してください',
            'min' => 'ページ番号は1以上で入力してください',
        ],
        'limit' => [
            'integer' => '表示件数は整数で入力してください',
            'min' => '表示件数は1以上で入力してください',
        ],
        'sort_name' => [
            'in' => '名前のソート順はASCまたはDESCで入力してください',
        ],
        'sort_created' => [
            'in' => '作成日のソート順はASCまたはDESCで入力してください',
        ],
        'sort_updated' => [
            'in' => '更新日のソート順はASCまたはDESCで入力してください',
        ],
        'start_date' => [
            'date' => '開始日は日付形式で入力してください',
            'date_format' => '開始日はY-m-d形式で入力してください',
        ],
        'end_date' => [
            'date' => '終了日は日付形式で入力してください',
            'date_format' => '終了日はY-m-d形式で入力してください',
            'after_or_equal' => '終了日は開始日以降の日付を入力してください',
        ],
        'conversations_page' => [
            'integer' => '会話ページ番号は整数で入力してください',
            'min' => '会話ページ番号は1以上で入力してください',
        ],
        'conversations_limit' => [
            'integer' => '会話数上限は整数で入力してください',
            'min' => '会話数上限は1以上で入力してください',
            'max' => '会話数上限は100以下で入力してください',
        ],
        'sample_io_page' => [
            'integer' => 'サンプルページ番号は整数で入力してください',
            'min' => 'サンプルページ番号は1以上で入力してください',
        ],
        'sample_io_limit' => [
            'integer' => 'サンプル数上限は整数で入力してください',
            'min' => 'サンプル数上限は1以上で入力してください',
            'max' => 'サンプル数上限は50以下で入力してください',
        ],
    ],
];
