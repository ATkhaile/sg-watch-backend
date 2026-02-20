<?php
return [
    'list' => [
        'message' => 'クーポン一覧を取得しました。',
    ],
    'validation' => [
        'shop_id' => [
            'required' => '店舗IDは必須です。',
            'integer'  => '店舗IDは整数で入力してください。',
            'exists'   => '選択された店舗は存在しません。',
        ],
        'user_type' => [
            'required' => 'user_type は必須です。',
            'integer'  => 'user_type は数値で入力してください。',
            'in'       => 'user_type が不正です。',
        ],
    ],
];
