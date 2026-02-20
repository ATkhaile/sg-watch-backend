<?php

return [
    'validation' => [
        'code' => [
            'required' => 'コードは必須です。',
            'string' => 'コードは文字列である必要があります。',
        ],
    ],
    'login' => [
        'success' => 'ログインに成功しました。',
    ],
    'register' => [
        'success' => '登録に成功しました。',
    ],
    'error' => [
        'line_token' => 'LINEのアクセストークンの取得に失敗しました。',
        'line_email' => 'このLINEアカウントにはメールアドレスが登録されていません。',
        'line_linked' => 'このLINEアカウントは既に他のユーザーにリンクされています。',
        'account_black' => 'あなたのアカウントは停止されています。LINEからサポートにお問い合わせください。',
        'line_not_configured' => 'envファイルにLINE_LOGIN_CHANNEL_IDまたはLINE_LOGIN_CALLBACKがありません',
    ],
    'auth' => [
        'line_login_url_generated' => 'Lineの認証URLが正常に生成されました',
    ],
];
