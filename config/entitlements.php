<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Entitlements（デフォルトエンタイトルメント定義）
    |--------------------------------------------------------------------------
    |
    | システムで管理されるエンタイトルメントの定義。
    | Seederで自動登録され、アプリケーション全体で利用されます。
    |
    | controlled_by:
    |   - 'server': サーバー側のみで制御（API権限チェック等）
    |   - 'client': クライアント側のみで制御（UI表示/非表示等）
    |   - 'both': サーバー・クライアント両方で制御
    |
    | is_system: システムで自動管理されるかどうか
    |   - true: システム定義、削除不可
    |   - false: 手動追加可能、削除可能
    |
    */

    'default_entitlements' => [
        // ========================================
        // Admin & Access Control
        // ========================================
        [
            'code' => 'admin_ui_access',
            'name' => '管理者画面のUIの表示',
            'description' => '管理者画面へのアクセスを制御します。ONにすると管理者画面のUIが表示されます。',
            'type' => 'on_off',
            'default_value' => 'true',
            'category' => 'admin',
            'display_order' => 1,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'both',
            'metadata' => null,
        ],
        [
            'code' => 'paywall_disabled',
            'name' => '未加入時のPaywall非表示',
            'description' => '未加入ユーザーに対してPaywallを非表示にする機能。ONにするとPaywallが表示されなくなります。',
            'type' => 'on_off',
            'default_value' => 'true',
            'category' => 'access',
            'display_order' => 10,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'client',
            'metadata' => null,
        ],
        [
            'code' => 'shop_access',
            'name' => 'ショップ機能利用',
            'description' => 'ショップ機能へのアクセスを制御します。ONにするとショップ機能が利用可能になります。',
            'type' => 'on_off',
            'default_value' => 'true',
            'category' => 'access',
            'display_order' => 20,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'both',
            'metadata' => null,
        ],
        [
            'code' => 'profile_access',
            'name' => 'マイプロフィールの確認',
            'description' => 'マイプロフィールへのアクセスを制御します。ONにするとマイプロフィールが確認できます。',
            'type' => 'on_off',
            'default_value' => 'true',
            'category' => 'access',
            'display_order' => 30,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'both',
            'metadata' => null,
        ],
        [
            'code' => 'group_invite_permission',
            'name' => 'グループ招集権限',
            'description' => '相互フォローでなくてもコミュニティのグループにメンバーを招待・追加できる権限です。ONにするとグループへの招待が可能になります。',
            'type' => 'on_off',
            'default_value' => 'true',
            'category' => 'access',
            'display_order' => 40,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'both',
            'metadata' => null,
        ],

        // ========================================
        // Columns & Series Access Control
        // ========================================
        [
            'code' => 'column_access_count',
            'name' => 'コラム開封権限（消費型）',
            'description' => 'コラムを開封できる回数を管理します。シリーズ単位、コラム単位、または全体で残数を管理できます。',
            'type' => 'consumable',
            'default_value' => '{}',
            'category' => 'content',
            'display_order' => 100,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'both',
            'metadata' => [
                'value_format' => 'json',
                'json_structure' => [
                    'series_{id}' => 'number (残数)',
                    'column_{id}' => 'number (残数)',
                    'all' => 'number (全コラム合計残数)',
                ],
                'example' => [
                    'series_123' => 5,
                    'column_456' => 1,
                    'all' => 10,
                ],
            ],
        ],
        [
            'code' => 'column_unlimited_access',
            'name' => 'コラム無限開封権',
            'description' => '特定のシリーズやコラム、または全てのコラムを無限に開封できる権限です。',
            'type' => 'on_off',
            'default_value' => '{}',
            'category' => 'content',
            'display_order' => 101,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'both',
            'metadata' => [
                'value_format' => 'json',
                'json_structure' => [
                    'series' => 'array of series IDs',
                    'columns' => 'array of column IDs',
                    'all' => 'boolean',
                ],
                'example' => [
                    'series' => [123, 456],
                    'columns' => [789, 101],
                    'all' => true,
                ],
            ],
        ],

        // ========================================
        // AI & Feature Quotas
        // ========================================
        [
            'code' => 'ai_chat_monthly_limit',
            'name' => 'AIチャット月間上限',
            'description' => '月間で利用可能なAIチャット回数の上限。毎月1日にリセットされます。',
            'type' => 'quota',
            'default_value' => '0',
            'category' => 'ai',
            'display_order' => 200,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'server',
            'metadata' => [
                'reset_period' => 'monthly',
                'unit' => '回',
            ],
        ],
        [
            'code' => 'ai_tokens',
            'name' => 'AIトークン（消費型）',
            'description' => 'AI機能で使用できるトークン数。使用すると減少します。',
            'type' => 'consumable',
            'default_value' => '0',
            'category' => 'ai',
            'display_order' => 201,
            'is_active' => true,
            'is_system' => true,
            'controlled_by' => 'server',
            'metadata' => [
                'unit' => 'トークン',
            ],
        ],
    ],
];
