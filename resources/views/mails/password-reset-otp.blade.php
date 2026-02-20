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
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            color: #007BFF;
            padding: 15px;
            margin: 25px 0;
            text-align: center;
            background-color: #e9ecef;
            border-radius: 8px;
            letter-spacing: 8px;
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
        .footer {
            font-size: 12px;
            color: #888;
            line-height: 1.8;
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
            パスワードリセット認証コード
        </div>

        <div class="content">
            {{ config('mail.from.name') }}をご利用いただき、ありがとうございます。<br>
            パスワードリセットのための確認コードをお送りします。
        </div>

        <div class="content">
            以下の認証コードを入力して、パスワードリセットを完了してください。
        </div>

        <div class="verification-code">
            {{ $code }}
        </div>

        <div class="content">
            この認証コードの有効期限は{{ (int) ceil(config('auth.password_otp.expires_in', 200) / 60) }}分間です。<br>
            有効期限が切れた場合は、お手数ですが再度リクエストしてください。
        </div>

        <div class="warning-box">
            <div class="warning-title">■ ご注意</div>
            <div class="warning-item">・この認証コードを第三者と共有しないでください</div>
            <div class="warning-item">・このメールに心当たりがない場合は、破棄してください</div>
        </div>

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
