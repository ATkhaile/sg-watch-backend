<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
            }
            .email-container {
                margin: 0 auto;
                padding: 20px;
                max-width: 600px;
                background-color: #f9f9f9;
                border: 1px solid #ddd;
            }
            .header {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 20px;
            }
            .content {
                margin-bottom: 20px;
            }
            .verification-code {
                font-size: 24px;
                font-weight: bold;
                color: #007BFF;
                padding: 10px;
                margin: 20px 0;
                text-align: center;
                background-color: #e9ecef;
                border-radius: 5px;
            }
            .footer {
                font-size: 12px;
                color: #666;
            }
        </style>
    </head>
    <body>
        <div class="email-container">
            <div class="header">
                {{$name}}様
            </div>
            <div class="content">
                {{ config('mail.from.name') }}をご利用いただき、ありがとうございます。<br>
                ログイン認証のための確認コードをお送りします。
            </div>
            <div class="content">
                以下の認証コードを入力して、ログインを完了してください。
            </div>
            <div class="verification-code">
                {{$code}}
            </div>
            <div class="content">
                この認証コードの有効期限は{{config('auth.email_2fa.code_expires_in', 15)}}分間です。<br>
                有効期限が切れた場合は、お手数ですが再度ログインを試みてください。
            </div>
            <div class="content">
                このメールに心当たりがない場合は、破棄していただきますようお願いいたします。
            </div>
            <div class="footer">
                ※本メールは送信専用のため、返信いただいてもお答えできません。<br>
                {{ config('mail.from.name') }}運営事務局<br>
                お問い合わせ先: {{ config('mail.from.address') }}
            </div>
        </div>
    </body>
</html>