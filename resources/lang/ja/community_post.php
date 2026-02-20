<?php

return [
    'list' => [
        'message' => 'コミュニティ投稿一覧の取得に成功しました。',
    ],
    'create' => [
        'success' => 'コミュニティ投稿の作成に成功しました。',
        'failed' => 'コミュニティ投稿の作成に失敗しました。',
    ],
    'find' => [
        'message' => 'コミュニティ投稿の詳細取得に成功しました。',
    ],
    'update' => [
        'success' => 'コミュニティ投稿の更新に成功しました。',
        'failed' => 'コミュニティ投稿の更新に失敗しました。',
    ],
    'delete' => [
        'success' => 'コミュニティ投稿の削除に成功しました。',
        'failed' => 'コミュニティ投稿の削除に失敗しました。',
    ],
    'toggle' => [
        'success' => 'リアクションを更新しました。',
        'failed' => 'リアクションの更新に失敗しました。',
    ],
    'setting' => [
        'success' => '閲覧設定を更新しました。',
        'failed' => '閲覧設定の更新に失敗しました。',
    ],
    'user_setting' => [
        'message' => 'コミュニティ投稿の表示設定を取得しました。',
    ],
    'user_reaction' => [
        'message' => 'リアクションしたユーザー一覧を取得しました。',
    ],
    'message' => [
        'unauthorized' => 'このグループには投稿する権限がありません。',
    ],
    'bookmark' => [
        'success' => '投稿をブックマークしました。',
        'failed' => 'ブックマークの更新に失敗しました。',
    ],
    'action' => [
        'success' => '成功しました。',
        'failed' => '失敗しました。',
    ],
    'report' => [
        'success' => '報告を受け付けました。',
        'failed' => '報告の送信に失敗しました。',
    ],
    'friend_list' => [
        'message' => 'フレンド一覧の取得に成功しました。',
    ],
    'repost' => [
        'create' => [
            'success' => 'リポストしました。',
            'failed' => 'リポストに失敗しました。',
        ],
        'list' => [
            'message' => 'リポスト一覧です。',
        ],
    ],
    'validation' => [
        'id' => [
            'required' => 'IDは必須です。',
            'integer' => 'IDは整数でなければなりません。',
            'exists' => 'コミュニティ投稿が存在しません。',
        ],
        'not_found' => '該当するデータが見つかりません。',
        'content' => [
            'required' => '内容は必須です。',
            'string' => '内容は文字列でなければなりません。',
            'or_file_required' => '内容またはファイルのいずれかを入力してください。',
        ],
        'can_see_post' => [
            'in' => 'can_see_postはallまたはuser_followでなければなりません。',
        ],
        'can_see_comment' => [
            'in' => 'can_see_commentはall、user_follow、disabledのいずれかでなければなりません。',
        ],
        'file_attachs' => [
            'required' => '添付ファイルは必須です。',
            'array' => '添付ファイルは配列でなければなりません。',
            'file' => '各添付ファイルは有効なファイルでなければなりません。',
            'image' => '各添付ファイルは画像でなければなりません。',
            'max' => '各添付ファイルは5MB以下でなければなりません。',
            'mimetypes' => '各添付ファイルは指定された形式のファイルでなければなりません。',
        ],
        'file_remove_ids' => [
            'array' => '削除ファイルIDは配列でなければなりません。',
            'integer' => '削除ファイルIDは整数でなければなりません。',
            'exists' => '削除対象の添付ファイルが存在しません。',
        ],
        'page' => [
            'integer' => 'ページは整数でなければなりません。',
            'min' => 'ページは1以上でなければなりません。',
        ],
        'limit' => [
            'integer' => 'リミットは整数でなければなりません。',
            'min' => 'リミットは1以上でなければなりません。',
        ],
        'reaction_code' => [
            'required' => 'リアクションは必須です。',
            'string' => 'リアクションは文字列で指定してください。',
            'in' => '選択したリアクションは無効です。',
        ],
        'comment' => [
            'required_without' => 'コメントまたはファイルを入力してください。',
            'string' => 'コメントは文字列で入力してください。',
        ],
        'file_attach' => [
            'required_without' => 'コメントまたはファイルを入力してください。',
            'mimes' => 'アップロードできるファイルは :values のみです。',
            'max' => '添付ファイルは :max KB 以下にしてください。',
        ],
        'group_id' => [
            'integer' => 'グループIDは整数で指定してください。',
            'exists' => '選択したグループは存在しません。',
        ],
        'tags' => [
            'array' => 'タグは配列で指定してください。',
            'string' => 'タグ名は文字列で指定してください。',
        ],
        'set_see_post' => [
            'boolean' => 'set_see_post は true または false を指定してください。',
        ],
        'set_see_comment' => [
            'boolean' => 'set_see_comment は true または false を指定してください。',
        ],
        'can_see_post' => [
            'in' => '投稿の公開範囲は all または user_follow を指定してください。',
        ],
        'can_see_comment' => [
            'in' => 'コメントの公開範囲は all, user_follow, disabled のいずれかを指定してください。',
        ],
        'type' => [
            'required' => '種別は必須項目です。',
            'in' => '種別の値が不正です。',
        ],
        'share_post_id' => [
            'integer' => '共有元投稿IDは整数で指定してください。',
            'exists' => '指定された共有元の投稿は存在しません。',
        ],
        'user_id' => [
            'required' => 'ユーザーIDは必須です。',
            'integer' => 'ユーザーIDは数値で指定してください。',
            'exists' => '指定されたユーザーは存在しません。',
            'not_self' => '自分自身をブロックすることはできません。',
        ],
        'action' => [
            'required' => 'アクションは必須です。',
            'string' => 'アクションは文字列で指定してください。',
            'in' => 'アクションが無効です。',
        ],
        'reason' => [
            'required' => '報告理由は必須です。',
            'string' => '報告理由は文字列で指定してください。',
            'min' => '報告理由を入力してください。',
            'max' => '報告理由は1000文字以内で入力してください。',
        ],
        'is_quote' => [
            'boolean' => '引用フラグの形式が正しくありません。',
        ],

        'quote_id' => [
            'integer' => '引用投稿IDは数値で指定してください。',
            'min' => '引用投稿IDは1以上で指定してください。',
            'exists' => '引用投稿が存在しません。',
        ],
        'search' => [
            'string' => '検索条件は文字列で指定してください。',
            'max' => '検索条件は255文字以内で指定してください。',
        ],
        'media_upload_id' => [
            'exists' => '指定されたメディアアップロードは存在しません。',
        ],
        'quote_comment_id' => [
            'integer' => '引用コメントIDは数値で指定してください。',
            'exists' => '引用コメントが存在しません。',
        ],
        'flag_story' => [
            'boolean' => 'flag_story は true または false を指定してください。',
        ],
        'story_creator_id' => [
            'required_if' => 'ストーリー投稿の場合、作成者を指定してください。',
            'exists' => '指定された作成者は存在しません。',
        ],
        'name' => [
            'required' => '名前は必須です。',
            'string' => '名前は文字列でなければなりません。',
            'max' => '名前は255文字以下でなければなりません。',
        ],
        'avatar_file' => [
            'image' => 'アバターファイルは画像でなければなりません。',
            'mimes' => 'アバターファイルの形式は :values のいずれかでなければなりません。',
            'max' => 'アバターファイルは100MB以下でなければなりません.',
        ],
        'description' => [
            'string' => '説明は文字列でなければなりません。',
            'max' => '説明は5000文字以下でなければなりません。',
        ],
        'search_type' => [
            'in' => '検索タイプの値が不正です。',
        ],
        'cover_photo' => [
            'image' => 'カバーフォトは画像でなければなりません。',
            'mimes' => 'カバーフォトの形式は :values のいずれかでなければなりません。',
            'max' => 'カバーフォトは100MB以下でなければなりません.',
        ],
        'target_user_id' => [
            'integer' => 'ターゲットユーザーIDは数値で指定してください。',
            'exists' => 'ターゲットユーザーが存在しません。',
        ],
        'mentioned_user_ids' => [
            'array' => 'メンションユーザーIDは配列で指定してください。',
            'integer' => 'メンションユーザーIDは数値で指定してください。',
            'exists' => 'メンションされたユーザーが存在しません。',
        ],
    ],
    'gif' => [
        'message' => 'GIFファイルの取得に成功しました。',
    ],
    'check_draft' => [
        'message' => '下書きの確認に成功しました。',
    ],
];
