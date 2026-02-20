<?php

return [
    'list' => [
        'message' => '通知一覧が正常に取得されました',
    ],
    'detail' => [
        'message' => '通知詳細が正常に取得されました',
    ],
    'create' => [
        'success' => '通知が正常に作成されました',
        'failed' => '通知の作成に失敗しました',
    ],
    'update' => [
        'success' => '通知が正常に更新されました',
        'failed' => '通知の更新に失敗しました',
    ],
    'delete' => [
        'success' => '通知が正常に削除されました',
        'failed' => '通知の削除に失敗しました',
    ],
    'validation' => [
        'title' => [
            'required' => 'タイトルは必須です',
            'string' => 'タイトルは文字列である必要があります',
            'max' => 'タイトルは255文字を超えることはできません',
        ],
        'content' => [
            'required' => 'コンテンツは必須です',
            'string' => 'コンテンツは文字列である必要があります',
            'max' => 'コンテンツは10000文字を超えることはできません',
        ],
        'type' => [
            'required' => 'プッシュタイプは必須です',
            'string' => 'プッシュタイプは文字列である必要があります',
            'max' => 'プッシュタイプは50文字を超えることはできません',
            'in' => 'プッシュタイプは以下のいずれかである必要があります: ' . implode(', ', \App\Enums\PushType::getValues()),
        ],
        'push_datetime' => [
            'date_format' => 'プッシュ日時はY/m/d H:i:s形式である必要があります',
        ],
        'push_now_flag' => [
            'boolean' => 'プッシュ今すぐフラグはtrueまたはfalseである必要があります',
        ],
        'id' => [
            'required' => 'IDは必須です',
            'integer' => 'IDは整数である必要があります',
            'exists' => '通知が見つかりません',
        ],
        'image_file' => [
            'image' => 'アップロードは画像ファイルである必要があります',
            'max' => 'アップロードファイルサイズは10MBを超えることはできません',
        ],
        'sender_type' => [
            'required' => '送信者タイプは必須です',
            'string' => '送信者タイプは文字列である必要があります',
            'max' => '送信者タイプは50文字を超えることはできません',
            'in' => '送信者タイプは以下のいずれかである必要があります: ' . implode(', ', \App\Enums\SenderType::getValues()),
        ],
    ],
];
