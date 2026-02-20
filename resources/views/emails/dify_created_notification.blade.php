<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>【株式会社Game】 サービスが正常に認証されました</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
            }
            .email-container {
                margin: 0 auto;
                padding: 20px;
                max-width: 800px;
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
            .link {
                color: #007BFF;
                text-decoration: none;
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
                {{$user->full_name}}様
            </div>
            <div class="content">
                こんにちは！ <br />
                サービスが正常に作成されました。
            </div>
            <div class="content">
                再開用のコードを下記にお送りします。前回の続きからチャットを再開する際にご入力ください。
            </div>
            <div class="content">
                当サービスをご利用いただき、誠にありがとうございます。
            </div>
            <div class="content">
                ご不明な点があれば、 いつでもお気軽にご連絡くださいね。これからもどうぞよろしくお願いします！
            </div>
            <div class="footer">
                【株式会社Game】
            </div>
        </div>
    </body>
</html>
