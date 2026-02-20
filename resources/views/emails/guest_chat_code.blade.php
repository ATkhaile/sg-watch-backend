<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>【株式会社Game】 個別相談チャット再開リンク</title>
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
                {{$name}}様
            </div>
            <div class="content">
                こんにちは！ <br />
                個別相談チャットをご利用いただき、 ありがとうございます。
            </div>
            <div class="content">
                再開用のコードを下記にお送りします。前回の続きからチャットを再開する際にご入力ください。
            </div>
            <div class="content">
                チャット再開用コード： <b>{{$code}}</b>
            </div>
            <div class="content">
                ご不明な点があれば、 いつでもお気軽にご連絡くださいね。これからもどうぞよろしくお願いします！
            </div>
            <div class="footer">
                Game採用担当より
            </div>
        </div>
    </body>
</html>
