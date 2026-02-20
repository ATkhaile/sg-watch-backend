<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Hiragino Kaku Gothic Pro', 'Yu Gothic', 'Meiryo', sans-serif;
            line-height: 1.8;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
            background-color: #ffffff;
        }
        .logo {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }
        .header {
            font-size: 16px;
            color: #333;
            margin-bottom: 20px;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
            margin: 30px 0;
        }
        .content {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-item {
            margin-bottom: 12px;
        }
        .info-item:last-child {
            margin-bottom: 0;
        }
        .info-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 4px;
        }
        .info-value {
            font-size: 14px;
            color: #333;
            font-weight: 500;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 40px;
            background-color: #ffae2b;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 25px;
            font-size: 14px;
            font-weight: bold;
        }
        .button:visited,
        .button:hover,
        .button:active {
            color: #ffffff !important;
        }
        .link-section {
            margin: 25px 0;
            padding: 15px;
            background-color: #fafafa;
            border-radius: 8px;
        }
        .link-item {
            margin: 10px 0;
        }
        .link-item a {
            color: #ffae2b;
            text-decoration: none;
            font-size: 14px;
        }
        .link-item a:hover {
            text-decoration: underline;
        }
        .divider {
            border: none;
            border-top: 1px solid #e0e0e0;
            margin: 30px 0;
        }
        .footer {
            font-size: 12px;
            color: #888;
            line-height: 1.8;
        }
        .footer a {
            color: #06c755;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="logo">{{ config('mail.from.name', 'TERAKONA') }}</div>

        <div class="header">
            {{ $data['name'] }}様
        </div>

        <div class="title">
            登録ありがとうございます！
        </div>

        <div class="content">
            {{ config('mail.from.name', 'TERAKONA') }}への会員登録が完了しました。<br>
            以下の情報でログインが可能です。
        </div>

        <div class="info-box">
            <div class="info-item">
                <div class="info-label">メールアドレス</div>
                <div class="info-value">{{ $data['email'] }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">会員ID</div>
                <div class="info-value">{{ $data['user_id'] ?? '（未設定）' }}</div>
            </div>
        </div>

        <div class="content">
            ※会員IDまたはメールアドレスのどちらでもログインできます。
        </div>

        @if(!empty($data['login_url']))
        <div class="button-container">
            <a href="{{ $data['login_url'] }}" class="button" style="color: #ffffff !important;">ログインする</a>
        </div>
        @endif

        <div class="link-section">
            @if(!empty($data['login_url']))
            <div class="link-item">
                ▼ ログイン画面<br>
                <a href="{{ $data['login_url'] }}">{{ $data['login_url'] }}</a>
            </div>
            @endif
            @if(!empty($data['forgot_password_url']))
            <div class="link-item">
                ▼ パスワードをお忘れの方<br>
                <a href="{{ $data['forgot_password_url'] }}">{{ $data['forgot_password_url'] }}</a>
            </div>
            @endif
        </div>

        <hr class="divider">

        <div class="footer">
            ※このメールに心当たりがない場合は、破棄していただきますようお願いいたします。<br><br>
            ※本メールは送信専用のため、返信いただいてもお答えできません。<br>
            {{ config('mail.from.name', 'TERAKONA') }}運営事務局<br>
            @if(config('mail.contact_url'))
            お問い合わせ先: <a href="{{ config('mail.contact_url') }}">お問い合わせフォーム</a>
            @else
            お問い合わせ先: {{ config('mail.from.address') }}
            @endif
        </div>
    </div>
</body>
</html>
