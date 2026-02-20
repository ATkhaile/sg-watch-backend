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
            word-break: break-all;
        }
        .link-item a:hover {
            text-decoration: underline;
        }
        .steps-box {
            background-color: #fff8e1;
            border: 1px solid #ffe082;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .steps-title {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 12px;
        }
        .step-item {
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            padding-left: 20px;
            position: relative;
        }
        .step-item::before {
            content: attr(data-step);
            position: absolute;
            left: 0;
            color: #ffae2b;
            font-weight: bold;
        }
        .warning-box {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            border-radius: 8px;
            padding: 15px;
            margin: 25px 0;
        }
        .warning-title {
            font-size: 14px;
            font-weight: bold;
            color: #c62828;
            margin-bottom: 8px;
        }
        .warning-item {
            font-size: 13px;
            color: #555;
            margin-bottom: 4px;
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
            color: #ffae2b;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="logo">{{ config('mail.from.name', 'TERAKONA') }}</div>

        <div class="header">
            {{ $name }}様
        </div>

        <div class="title">
            メールアドレス確認のお願い
        </div>

        <div class="content">
            ご登録ありがとうございます。<br>
            以下のボタンをクリックして、メールアドレスの確認を完了してください。
        </div>

        <div class="button-container">
            <a href="{{ $verificationUrl }}" class="button" style="color: #ffffff !important;">メールアドレスを確認する</a>
        </div>

        <div class="link-section">
            <div class="link-item">
                ▼ 上記ボタンが機能しない場合は、以下のURLをブラウザに貼り付けてください<br>
                <a href="{{ $verificationUrl }}">{{ $verificationUrl }}</a>
            </div>
        </div>

        <div class="steps-box">
            <div class="steps-title">■ 確認手順</div>
            <div class="step-item" data-step="1.">上記のボタンまたはURLをクリックしてください</div>
            <div class="step-item" data-step="2.">確認完了画面が表示されます</div>
            <div class="step-item" data-step="3.">その後、ログインしてサービスをご利用いただけます</div>
        </div>

        <div class="warning-box">
            <div class="warning-title">■ ご注意</div>
            <div class="warning-item">・このリンクは{{ config('auth.email_verification.timeout', 60) }}分間有効です</div>
            <div class="warning-item">・このリンクを第三者と共有しないでください</div>
            <div class="warning-item">・このメールに心当たりがない場合は、破棄してください</div>
        </div>

        <hr class="divider">

        <div class="footer">
            ※本メールは送信専用のため、返信いただいてもお答えできません。<br><br>
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
