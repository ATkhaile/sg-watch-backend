<?php

return [
    'update' => [
        'success' => 'タームが正常に更新されました',
        'failed' => 'タームの更新に失敗しました'
    ],
    'detail' => [
        'message' => 'タームの詳細'
    ],
    'validation' => [
        'id' => [
            'required' => 'IDは必須です',
            'integer' => 'IDは整数である必要があります',
        ],
        'shop_id' => [
            'required' => 'ショップIDは必須です',
            'integer' => 'ショップIDは整数である必要があります',
        ],
        'content' => [
            'required' => 'コンテンツは必須です',
            'string' => 'コンテンツは文字列である必要があります',
        ],
    ]
];
