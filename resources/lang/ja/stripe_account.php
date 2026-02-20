<?php

return [
    'create' => [
        'success' => 'ストライプアカウント作成成功',
        'failed' => 'ストライプアカウント作成失敗'
    ],
    'update' => [
        'success' => 'ストライプアカウント更新成功',
        'failed' => 'ストライプアカウント更新失敗'
    ],
    'delete' => [
        'success' => 'ストライプアカウント削除成功',
        'failed' => 'ストライプアカウント削除失敗'
    ],
    'list' => [
        'message' => 'ストライプアカウント一覧',
    ],
    'find' => [
       'message' => 'ストライプアカウント詳細',
    ],
    'customers' => [
        'subscriptions' => [
            'message' => 'お客様登録一覧',
            'active' => 'アクティベート済み',
            'inactive' => '未アクティベート'
        ],
    ],
    'not_found' => 'ストライプアカウントが見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'Idが整数である必要があります',
           'exists' => 'Idが存在しません'
        ],
        'name' => [
            'required' => 'ストライプアカウント名が必要です',
            'string' => 'ストライプアカウント名は文字列である必要があります',
            'max' => 'ストライプアカウント名は255文字を超えることはできません'
        ],
        'price' => [
            'required' => '価格が必要です',
            'integer' => '価格は整数である必要があります'
        ],
        'plan_type' => [
            'required' => 'ストライプアカウントタイプが必要です',
            'integer' => 'ストライプアカウントタイプは整数である必要があります'
        ],
        'sns_limits' => [
            'array' => 'SNS制限は配列である必要があります'
        ],
        'sns_developer' => [
            'array' => 'SNS開発者は配列である必要があります'
        ],
        'stripe_plan_id' => [
            'required' => 'ストライプアカウントプランIDが必要です',
            'string' => 'ストライプアカウントプランIDは文字列である必要があります'
        ],
        'stripe_key' => [
            'required' => 'Stripeキーが必要です',
            'string' => 'Stripeキーは文字列である必要があります'
        ],
        'stripe_secret' => [
            'required' => 'Stripeシークレットが必要です',
            'string' => 'Stripeシークレットは文字列である必要があります'
        ],
        'stripe_payment_link' => [
            'required' => 'Stripe支払いリンクが必要です',
            'string' => 'Stripe支払いリンクは文字列である必要があります'
        ],
        'stripe_webhook_secret' => [
            'required' => 'Stripe Webhookシークレットが必要です',
            'string' => 'Stripe Webhookシークレットは文字列である必要があります'
        ],
        'cancel_hours' => [
            'numeric' => 'キャンセル時間は数値である必要があります',
            'min' => 'キャンセル時間は0以上である必要があります'
        ],
        'search_name' => [
            'max' => 'プラン名は255文字を超えることはできません'
        ],
        'search_name_like' => [
            'boolean' => '検索名は真偽値である必要があります'
        ],
        'search_name_not' => [
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
    'products' => [
        'message' => '商品を正常に取得しました'
    ],
    'prices' => [
        'message' => '価格を正常に取得しました'
    ],
    'payment_links' => [
        'message' => '支払いリンクを正常に取得しました'
    ],
    'dashboard_stats' => [
        'refresh' => [
            'success' => 'ダッシュボード統計を更新しました',
            'failed' => 'ダッシュボード統計の更新に失敗しました'
        ],
        'refresh_all' => [
            'success' => '全アカウントのダッシュボード統計を更新しました',
            'failed' => '全アカウントのダッシュボード統計の更新に失敗しました'
        ],
        'errors' => [
            'account_not_found' => 'Stripeアカウントが見つかりません',
            'api_key_not_configured' => 'このアカウントにStripe APIキーが設定されていません'
        ]
    ],
    'sync' => [
        'backfill' => [
            'success' => 'バックフィルが完了しました',
            'failed' => 'バックフィルに失敗しました',
            'completed_with_errors' => 'バックフィルが完了しましたが、一部エラーが発生しました'
        ],
        'incremental' => [
            'success' => '差分同期が完了しました',
            'failed' => '差分同期に失敗しました',
            'completed_with_errors' => '差分同期が完了しましたが、一部エラーが発生しました'
        ],
        'errors' => [
            'unsupported_object_type' => 'サポートされていないオブジェクトタイプです'
        ]
    ]
];
