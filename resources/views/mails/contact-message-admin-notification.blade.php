<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新しいお問い合わせ</title>
</head>
<body style="font-family: 'Hiragino Sans', 'Hiragino Kaku Gothic ProN', Meiryo, sans-serif; line-height: 1.8; color: #333; margin: 0; padding: 20px; background-color: #f5f5f5;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="background-color: #ef4444; padding: 20px; text-align: center;">
            <h1 style="color: #ffffff; margin: 0; font-size: 20px;">【要対応】新しいお問い合わせ</h1>
        </div>

        <!-- Content -->
        <div style="padding: 30px;">
            <p style="margin-bottom: 20px;">新しいお問い合わせがありました。</p>

            <!-- Ticket Info -->
            <div style="background-color: #fef2f2; border: 1px solid #fecaca; padding: 15px; margin-bottom: 20px; border-radius: 8px;">
                <p style="margin: 0 0 5px 0;"><strong>チケット番号:</strong> {{ $data['ticket_number'] }}</p>
                <p style="margin: 0;"><strong>受付日時:</strong> {{ now()->format('Y/m/d H:i:s') }}</p>
            </div>

            <!-- Sender Info -->
            <h3 style="border-bottom: 2px solid #ffae2b; padding-bottom: 10px; margin-top: 30px;">送信者情報</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; width: 100px; color: #666;">名前:</td>
                    <td style="padding: 8px 0;">{{ $data['name'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">所属:</td>
                    <td style="padding: 8px 0;">{{ $data['organization'] }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; color: #666;">メール:</td>
                    <td style="padding: 8px 0;">{{ $data['reply_email'] ?? '未入力' }}</td>
                </tr>
            </table>

            <!-- Message Content -->
            <h3 style="border-bottom: 2px solid #ffae2b; padding-bottom: 10px; margin-top: 30px;">お問い合わせ内容</h3>
            <p style="margin-bottom: 10px;"><strong>件名:</strong> {{ $data['subject'] }}</p>
            <div style="background-color: #f9f9f9; padding: 15px; border-radius: 8px; white-space: pre-wrap;">{{ $data['body'] }}</div>

            <!-- Attachments -->
            @if(isset($data['attachments_count']) && $data['attachments_count'] > 0)
            <h3 style="border-bottom: 2px solid #ffae2b; padding-bottom: 10px; margin-top: 30px;">添付ファイル</h3>
            <p>{{ $data['attachments_count'] }}件のファイルが添付されています。</p>
            @endif

            <!-- Action Button -->
            <div style="text-align: center; margin-top: 40px;">
                <a href="{{ $admin_url }}" style="display: inline-block; background-color: #ffae2b; color: #ffffff; text-decoration: none; padding: 15px 40px; border-radius: 30px; font-weight: bold;">管理画面で確認する</a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #1f1f1f; padding: 15px; text-align: center;">
            <p style="color: #888; margin: 0; font-size: 12px;">TERAKONA 管理者通知</p>
        </div>
    </div>
</body>
</html>
