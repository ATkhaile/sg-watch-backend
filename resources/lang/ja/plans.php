<?php

return [
    'create' => [
        'success' => 'プラン作成成功',
        'failed' => 'プラン作成失敗'
    ],
    'update' => [
        'success' => 'プラン更新成功',
        'failed' => 'プラン更新失敗'
    ],
    'delete' => [
        'success' => 'プラン削除成功',
        'failed' => 'プラン削除失敗'
    ],
    'list' => [
        'message' => 'プラン一覧',
    ],
    'find' => [
       'message' => 'プラン詳細',
    ],
    'customers' => [
        'subscriptions' => [
            'message' => 'お客様登録一覧',
            'active' => 'アクティベート済み',
            'inactive' => '未アクティベート'
        ],
    ],
    'not_found' => 'プランが見つかりません',
    'validation' => [
        'id' => [
           'required' => 'IDが必要です',
           'integer' => 'Idが整数である必要があります',
           'exists' => 'Idが存在しません'
        ],
        'name' => [
            'required' => 'プラン名が必要です',
            'string' => 'プラン名は文字列である必要があります',
            'max' => 'プラン名は255文字を超えることはできません'
        ],
        'price' => [
            'required' => '価格が必要です',
            'integer' => '価格は整数である必要があります'
        ],
        'plan_type' => [
            'required' => 'プランタイプが必要です',
            'integer' => 'プランタイプは整数である必要があります'
        ],
        'sns_limits' => [
            'array' => 'SNS制限は配列である必要があります'
        ],
        'sns_developer' => [
            'array' => 'SNS開発者は配列である必要があります'
        ],
        'stripe_plan_id' => [
            'required' => 'StripeプランIDが必要です',
            'required_without' => '支払いリンクが提供されていない場合、StripeプランIDが必要です',
            'string' => 'StripeプランIDは文字列である必要があります'
        ],
        'stripe_price_id' => [
            'required_without' => '支払いリンクが提供されていない場合、Stripe価格IDが必要です',
            'string' => 'Stripe価格IDは文字列である必要があります'
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
            'required_without_all' => 'プランIDと価格IDが提供されていない場合、Stripe支払いリンクが必要です',
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
    ]
];
