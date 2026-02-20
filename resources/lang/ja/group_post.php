<?php

return [
    'create' => [
        'success' => 'グループ投稿の作成に成功しました。',
        'failed' => 'ニュースの作成に失敗しました。'
    ],
    'update' => [
        'success' => 'グループ投稿の更新に成功しました。',
        'failed' => 'ニュースの更新に失敗しました。'
    ],
    'delete' => [
        'success' => 'グループ投稿の削除に成功しました。',
        'failed' => 'ニュースの削除に失敗しました。'
    ],
    'list' => [
        'message' => 'グループ投稿一覧'
    ],
    'find' => [
        'message' => 'グループ投稿の詳細'
    ],
    'import' => [
        'success' => 'グループ投稿のインポートに成功しました。',
        'failed' => 'ニュースのインポートに失敗しました。'
    ],
    'not_found' => 'グループ投稿が見つかりません。',
    'toggle' => [
        'success' => 'リアクションの更新に成功しました。',
        'failed'  => '投稿のリアクション更新に失敗しました。',
    ],
    'detail' => [
        'message' => 'グループ投稿の詳細取得に成功しました。',
    ],
    'follow' => [
        'message' => 'フォロー一覧',
        'create' => [
            'success' => 'フォロー状態の更新に成功しました。',
            'failed'  => 'フォロー状態の更新に失敗しました。',
        ],
        'update' => [
            'success' => 'フォローリクエストを更新しました。',
            'failed'  => 'フォローリクエストの更新に失敗しました。',
        ],
        'already_approved' => 'すでにフォローされています。',
        'not_found' => 'フォローリクエストが見つかりません。',
    ],
    'unfollow_list' => [
        'message' => '未フォローユーザー一覧',
    ],
    'validation' => [
        'id' => [
            'required' => 'IDは必須項目です。',
            'integer' => 'IDは整数でなければなりません。',
            'exists' => 'グループ投稿が見つかりません。'
        ],
        'remove_thumbnail' => [
            'boolean' => 'サムネイル削除は真偽値でなければなりません。'
        ],
        'reaction_code' => [
            'required' => 'リアクションコードは必須です。',
            'string' => 'リアクションコードは文字列でなければなりません。',
            'max' => 'リアクションコードは50文字以内で入力してください。',
        ],
        'title' => [
            'required' => 'タイトルは必須項目です。',
            'string' => 'タイトルは文字列でなければなりません。',
            'max' => 'タイトルは255文字以内で入力してください。'
        ],
        'content' => [
            'required' => '内容は必須項目です。',
            'string' => '内容は文字列でなければなりません。'
        ],
        'category_id' => [
            'exists' => 'カテゴリが見つかりません。'
        ],
        'tag_ids' => [
            'array' => 'タグIDは配列でなければなりません。',
            'exists' => 'タグが見つかりません。'
        ],
        'search_title' => [
            'max' => '検索タイトルは255文字以内で入力してください。'
        ],
        'search_title_like' => [
            'boolean' => '検索タイトルLIKEは真偽値でなければなりません。'
        ],
        'search_title_not' => [
            'boolean' => '検索タイトルNOTは真偽値でなければなりません。'
        ],
        'search_category' => [
            'exists' => 'カテゴリが見つかりません。'
        ],
        'search_category_like' => [
            'boolean' => '検索カテゴリLIKEは真偽値でなければなりません。'
        ],
        'search_category_not' => [
            'boolean' => '検索カテゴリNOTは真偽値でなければなりません。'
        ],
        'search_tags' => [
            'exists' => 'タグが見つかりません。'
        ],
        'search_tags_like' => [
            'boolean' => '検索タグLIKEは真偽値でなければなりません。'
        ],
        'search_tags_not' => [
            'boolean' => '検索タグNOTは真偽値でなければなりません。'
        ],
        'page' => [
            'integer' => 'ページは整数でなければなりません。',
            'min' => 'ページは1以上でなければなりません。'
        ],
        'per_page' => [
            'integer' => '1ページあたりの件数は整数でなければなりません。',
            'min'     => '1ページあたりの件数は1以上でなければなりません。',
        ],
        'search' => [
            'string' => '検索キーワードは文字列でなければなりません。',
            'max'    => '検索キーワードは255文字以内で入力してください。',
        ],
        'limit' => [
            'integer' => 'リミットは整数でなければなりません。',
            'min' => 'リミットは1以上でなければなりません。'
        ],
        'sort_title' => [
            'in' => 'タイトルのソート順はASCまたはDESCでなければなりません。'
        ],
        'sort_category' => [
            'in' => 'カテゴリのソート順はASCまたはDESCでなければなりません。'
        ],
        'sort_created' => [
            'in' => '作成日のソート順はASCまたはDESCでなければなりません。'
        ],
        'sort_updated' => [
            'in' => '更新日のソート順はASCまたはDESCでなければなりません。'
        ],
        'file' => [
            'required' => 'ファイルは必須項目です。',
            'file' => '有効なファイルを指定してください。',
            'mimes' => 'ファイルはCSV形式でなければなりません。',
            'max' => 'ファイルサイズは10GB以内でなければなりません。'
        ],
        'import' => [
            'template_id_invalid' => 'テンプレートIDは正の整数でなければなりません。',
            'title_required' => 'タイトルは必須項目です。',
            'title_string' => 'タイトルは文字列でなければなりません。',
            'title_max' => 'タイトルは255文字以内で入力してください。',
            'body_required' => '本文は必須項目です。',
            'body_string' => '本文は文字列でなければなりません。',
            'body_max' => '本文は10000文字以内で入力してください。',
            'status_required' => 'ステータスは必須項目です。',
            'status_invalid' => 'ステータスは1または2でなければなりません。',
            'news_not_found' => 'グループ投稿が存在しないか、削除されています。',
            'category_string' => 'カテゴリは文字列でなければなりません。',
            'category_max' => 'カテゴリは255文字以内で入力してください。',
            'tag_string' => 'タグは文字列でなければなりません。',
            'tag_max' => 'タグは255文字以内で入力してください。',
            'empty_line' => '行をすべて空にすることはできません。',
            'category_create_failed' => 'カテゴリの作成に失敗しました。',
            'tag_create_failed' => 'タグの作成に失敗しました。',
        ],
        'group_id' => [
            'required' => 'グループIDは必須項目です。',
            'integer' => 'グループIDは整数でなければなりません。',
            'exists' => 'グループが見つかりません。'
        ],
        'type' => [
            'in' => '種別は「すべての投稿」「フォロー中ユーザーの投稿」「参加グループの投稿」のいずれかを指定してください。',
        ],
    ]
];
