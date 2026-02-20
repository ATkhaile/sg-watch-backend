<?php

return [
    'save' => [
        'success' => 'ワークリアクションが正常に保存されました。',
        'failed' => 'ワークリアクションの保存に失敗しました。',
    ],
    'validation' => [
        'work_management_id' => [
            'required' => 'ワーク管理IDフィールドは必須です。',
            'integer' => 'ワーク管理IDは整数である必要があります。',
            'exists' => '指定されたワーク管理IDは存在しません。',
        ],
        'reaction' => [
            'required' => 'リアクションフィールドは必須です。',
            'in' => '選択されたリアクションは無効です。',
        ],
    ],
];