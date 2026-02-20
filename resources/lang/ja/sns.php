<?php

return [
    'update' => [
        'success' => 'SNS情報が正常に更新されました',
        'failed' => 'SNS情報の更新に失敗しました',
    ],
    'validation' => [
        'name' => [
            'required' => '名前は必須です',
            'string' => '名前は文字列である必要があります',
            'max' => '名前は255文字以内で入力してください',
        ],
        'invite_code' => [
            'required' => '招待コードは必須です',
            'string' => '招待コードは文字列である必要があります',
            'max' => '招待コードは255文字以内で入力してください',
            'unique' => 'この招待コードは既に使用されています',
        ],
        'invite_code_used' => [
            'string' => '招待コードは文字列である必要があります',
            'max' => '招待コードは255文字以内で入力してください',
            'exists' => '無効な招待コードです',
            'already_has_inviter' => '既に招待者がいます',
            'cannot_use_own_code' => '自分の招待コードは使用できません',
        ],
    ],
];