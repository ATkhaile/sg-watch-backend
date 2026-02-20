<?php

return [
    'get' => [
        'message' => '招待情報が正常に取得されました',
    ],
    'create' => [
        'success' => '招待を正常に受け入れました',
        'failed' => '招待の受け入れに失敗しました',
        'inviter_not_found' => '無効な招待コードです',
        'user_not_found' => 'ユーザーが見つかりません',
        'already_has_inviter' => '既に招待者がいます',
    ],
    'validation' => [
        'invite_code' => [
            'required' => '招待コードは必須です',
            'string' => '招待コードは文字列である必要があります',
            'max' => '招待コードは255文字以内で入力してください',
            'exists' => '無効な招待コードです',
        ],
        'already_has_inviter' => '既に招待者がいます',
    ],
];