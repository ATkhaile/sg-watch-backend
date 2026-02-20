{{$data['name']}}様<br>
<br>
このたびは{{ $data['app_name'] }}をご利用いただき、誠にありがとうございます。<br>
<strong>アクティベートが正常に完了いたしました。</strong><br>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
【アクティベート内容】<br>
<table style="border-collapse: collapse; margin: 10px 0;">
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">{{ $data['purchase_type'] === 'plan' ? 'プラン名' : '商品名' }}：</td><td style="padding: 5px 0;"><strong>{{ $data['purchase_name'] }}</strong></td></tr>
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">種類：</td><td style="padding: 5px 0;">{{ $data['purchase_type'] === 'plan' ? 'プラン' : '商品' }}</td></tr>
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">アクティベート日時：</td><td style="padding: 5px 0;">{{ $data['activated_at'] }}</td></tr>
</table>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
<div style="background-color: #ecfdf5; padding: 20px; border-radius: 8px; border-left: 4px solid #10b981; margin: 15px 0;">
<strong style="color: #047857; font-size: 16px;">ご利用開始の準備が整いました</strong><br>
<span style="color: #047857;">購入した{{ $data['purchase_type'] === 'plan' ? 'プラン' : '商品' }}の機能が有効になりました。<br>
ログインして、すべての機能をお楽しみください。</span>
</div>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
【お支払い情報】<br>
<table style="border-collapse: collapse; margin: 10px 0;">
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">ご請求先メールアドレス：</td><td style="padding: 5px 0;"><strong>{{ $data['billing_email_masked'] }}</strong></td></tr>
</table>
<br>
<span style="color: #6b7280; font-size: 12px;">※セキュリティ保護のため、メールアドレスの一部を非表示にしています。</span><br>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
ご不明な点がありましたら、お気軽にお問い合わせください。<br>
<br>
今後とも{{ $data['app_name'] }}をよろしくお願いいたします。<br>
<br>
<span style="color: #6b7280; font-size: 12px;">※このメールは自動送信されています。</span><br>
