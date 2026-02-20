<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お問い合わせ受付完了</title>
</head>
<body style="font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif; line-height: 1.8; color: #333; margin: 0; padding: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background-color: #ffae2b; padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">TERAKONA</h1>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">
            <p style="margin-bottom: 20px;">{{ $data['name'] }} 様</p>

            <p style="margin-bottom: 20px;">
                お問い合わせいただきありがとうございます。<br>
                以下の内容で受け付けました。
            </p>

            <!-- Info Box -->
            <div style="background-color: #f9f9f9; border-left: 4px solid #ffae2b; padding: 20px; margin: 30px 0; border-radius: 0 8px 8px 0;">
                <p style="margin: 0 0 10px 0;"><strong>お問い合わせ番号:</strong> {{ $data['ticket_number'] }}</p>
                <p style="margin: 0;"><strong>件名:</strong> {{ $data['subject'] }}</p>
            </div>

            <p style="margin-bottom: 20px;">
                担当者より順次ご連絡いたします。<br>
                今しばらくお待ちくださいませ。
            </p>

            <p style="color: #888; font-size: 14px; margin-top: 40px;">
                ※このメールは自動送信です。このメールに直接返信されても対応できませんのでご了承ください。
            </p>
        </div>

        <!-- Footer -->
        <div style="background-color: #1f1f1f; padding: 20px; text-align: center;">
            <p style="color: #ffae2b; margin: 0 0 10px 0; font-weight: bold;">TERAKONA</p>
            <p style="color: #888; margin: 0; font-size: 12px;">&copy; {{ date('Y') }} TERAKONA. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
