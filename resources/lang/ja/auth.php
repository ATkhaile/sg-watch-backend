<?php

return [
    'failed' => '認証情報が記録と一致しません。',
    'password' => '入力されたパスワードが正しくありません。',
    'notfound' => 'ユーザーが見つかりません',
    'token_failed' => 'トークンが無効です',
    'token_expired' => 'トークンの有効期限が切れています',
    'throttle' => 'ログイン試行回数が多すぎます。:seconds 秒後に再試行してください。',
    'login' => [
        'success' => 'ログインに成功しました',
        'user_password_failed' => 'ユーザー名またはパスワードが正しくありません',
        'failed' => 'ログインに失敗しました',
        'invalid_token' => '無効なトークンです',
        'expired_token' => 'トークンの有効期限が切れています',
        'user_not_found' => 'ユーザーが見つかりません',
        'lat_login_updated_failed' => '最終ログイン時間の更新に失敗しました。',
        'verification_code_sent' => '確認コードを送信しました',
        'verification_code_invalid' => '確認コードが無効です'
    ],
    'logout' => [
        'success' => 'ログアウトに成功しました',
        'failed' => 'ログアウトに失敗しました'
    ],
    'change_password' => [
        'notfound' => 'ユーザーが見つかりません',
        'failed' => 'パスワードの変更に失敗しました',
        'success' => 'パスワードが変更されました',
    ],
    'reset_token' => [
        'invalid' => '無効なトークンです',
       'success' => 'パスワードがリセットされました'
     ],
     'forgot_password' => [
        'success' => 'パスワードのリセットに成功しました',
        'failed' => 'パスワードのリセットに失敗しました',
        'notfound' => 'ユーザーが見つかりません',
    ],
    'register' => [
        'success' => '登録に成功しました',
        'failed' => '登録に失敗しました',
        'email_verification_sent' => '確認メールを送信しました',
        'email_verification_success' => 'メールアドレスの確認が完了しました',
        'email_verification_failed' => 'メール確認の作成に失敗しました',
        'email_verification_error' => 'メール確認の処理中にエラーが発生しました',
        'affiliate_token_invalid' => 'アフィリエイトトークンが無効です。',
        'affiliate_token_used_or_expired' => 'アフィリエイトトークンは既に使用済み、または有効期限切れです。'
    ],
    'reset_password' => [
        'success' => 'パスワードがリセットされました',
        'failed' => 'パスワードのリセットに失敗しました',
        'notfound' => 'ユーザーが見つかりません',
    ],
    'password' => [
        'reset_success' => 'パスワードがリセットされました',
        'reset_failed' => 'パスワードのリセットに失敗しました',
        'change_success' => 'パスワードが変更されました',
        'change_failed' => 'パスワードの変更に失敗しました',
        'old_password_incorrect' => '古いパスワードが正しくありません'
    ],
    'validation' => [
        'password_old' => [
            'required' => '※パスワードを入力してください。',
            'min' => '※英数字大文字小文字を含めた8文字以上を入力してください。',
            'max' => 'パスワード は最大16文字でなければなりません。',
            'confirmed' => '※パスワードが一致しません。',
        ],
        'password' => [
           'required' => '※パスワードを入力してください。',
           'min' => '※英数字大文字小文字を含めた8文字以上を入力してください。',
           'max' => 'パスワード は最大16文字でなければなりません。',
           'confirmed' => '※パスワードが一致しません。',
        ],
        'password_confirmation' => [
           'required' => '※パスワードを入力してください。',
           'same' => '※パスワードが一致しません。',
        ],
        'first_name' => [
          'required' => '※名を入力してください。',
          'string' => '※名は文字列である必要があります。',
          'max' => '※名は最大50文字でなければなりません。',
        ],
        'last_name' => [
          'required' => '※姓を入力してください。',
          'string' => '※姓は文字列である必要があります。',
          'max' => '※姓は最大50文字でなければなりません。',
        ],
        'email' => [
           'required' => '※メールアドレスを入力してください。',
           'email' => '※有効なメールアドレスを入力してください。',
           'not_exists' => '※登録されていないメールアドレスです。',
           'exists' => '※登録されているメールアドレスです。',
           'unique' => '※登録されているメールアドレスです。',
           'required_without' => '※メールアドレスを入力してください。',
        ],
        'verification_code' => [
          'required' => '※確認コードを入力してください。',
        ],
        'invite_code' => [
            'string' => '※招待コードは文字列である必要があります',
            'max' => '※招待コードは255文字以内で入力してください',
            'exists' => '※無効な招待コードです',
        ],
        'gender' => [
            'in' => '※性別はmale、female、other、unknownのいずれかを指定してください',
        ],
        'birthday' => [
            'date' => '※生年月日は有効な日付を入力してください',
            'date_format' => '※生年月日はYYYY-MM-DD形式で入力してください',
            'before' => '※生年月日は今日より前の日付を指定してください',
        ],
        'avatar_url' => [
            'max' => '※アバターURLは500文字以内で入力してください',
        ],
    ],
    'update_profile' => [
        'success' => 'プロフィールが更新されました',
        'failed' => 'プロフィールの更新に失敗しました',
    ],
    'password_otp' => [
        'sent' => 'メールアドレスが存在する場合、確認コードが送信されました。',
        'invalid' => '確認コードが無効または期限切れです。',
        'too_many_attempts' => '試行回数が上限に達しました。新しいコードをリクエストしてください。',
        'verified' => '確認に成功しました。',
    ],
    'unauthorized' => '権限がありません。',
];
