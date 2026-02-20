<?php

return [
    'create' => [
        'success' => 'メディアアップロードが正常に作成されました',
        'failed' => 'メディアアップロードの作成に失敗しました'
    ],
    'update' => [
        'success' => 'メディアアップロードが正常に更新されました',
        'failed' => 'メディアアップロードの更新に失敗しました'
    ],
    'delete' => [
        'success' => 'メディアアップロードが正常に削除されました',
        'failed' => 'メディアアップロードの削除に失敗しました'
    ],
    'streaming' => [
        'enabled' => 'ストリーミングが正常に有効になりました',
        'disabled' => 'ストリーミングが正常に無効になりました',
        'failed' => 'ストリーミングステータスの更新に失敗しました'
    ],
    'list' => [
        'message' => 'メディアアップロード一覧',

    ],
    'find' => [
        'message' => 'メディアアップロード詳細',
    ],
    'not_found' => 'メディアアップロードが見つかりません',
    'validation' => [
        'id' => [
            'required' => 'IDは必須です',
            'integer' => 'IDは整数で入力してください',
            'exists' => 'メディアアップロードが見つかりません'
        ],
        'type' => [
            'required' => 'タイプは必須です',
            'string' => 'タイプは文字列で入力してください',
            'in' => 'タイプは以下のいずれかである必要があります: ' . implode(', ', \App\Enums\FileType::all())
        ],
        'files' => [
            'required' => '少なくとも1つのファイルが必要です',
            'array' => 'ファイルは配列である必要があります',
        ],
        'file' => [
            'required' => 'ファイルは必須です',
            'file' => 'ファイルは有効なファイルである必要があります',
            'max' => 'ファイルサイズは10GBを超えることはできません',
            'invalid_type' => '指定されたタイプに対してファイルタイプが無効です',
        ],
        'media_folder_id' => [
            'required' => 'メディアフォルダーは必須です',
            'integer' => 'メディアフォルダーは整数で入力してください',
            'exists' => 'メディアフォルダーが存在しません',
        ],
        'is_streaming' => [
            'required' => 'ストリーミングステータスは必須です',
            'boolean' => 'ストリーミングステータスはブール値でなければなりません',
        ],
    ],

    'validation_param' => [
        'sort_type' => [
            'in' => '並び替えのタイプ(sort_type)はascまたはdescでなければなりません。',
        ],
        'sort_file_name' => [
            'in' => 'ファイル名の並び替え(sort_file_name)はascまたはdescでなければなりません。',
        ],
        'sort_file_size' => [
            'in' => 'ファイルサイズの並び替え(sort_file_size)はascまたはdescでなければなりません。',
        ],
        'sort_created' => [
            'in' => '作成日の並び替え(sort_created)はascまたはdescでなければなりません。',
        ],
        'sort_updated' => [
            'in' => '更新日の並び替え(sort_updated)はascまたはdescでなければなりません。',
        ],
        'sort_deleted' => [
            'in' => '削除日の並び替え(sort_deleted)はascまたはdescでなければなりません。',
        ],
        'page' => [
            'integer' => 'ページは整数でなければなりません。',
            'min' => 'ページは1以上でなければなりません。',
        ],
        'limit' => [
            'integer' => '1ページあたりの件数は整数でなければなりません。',
            'min' => '1ページあたりの件数は1以上でなければなりません。',
            'max' => '1ページあたりの件数は最大100です。',
        ],
        'is_deleted' => [
            'sometimes' => 'isDeletedはtrueまたはfalseでなければなりません。',
            'in' => 'isDeletedは0、1、true、falseのいずれかでなければなりません。',
        ],
    ]
];
