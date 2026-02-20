{{$data['name']}}様<br>
<br>
このたびは{{ $data['app_name'] }}をご利用いただき、誠にありがとうございます。<br>
<strong>お支払いが正常に完了いたしました。</strong><br>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
【ご購入内容】<br>
<table style="border-collapse: collapse; margin: 10px 0;">
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">商品/プラン名：</td><td style="padding: 5px 0;"><strong>{{ $data['purchase_name'] }}</strong></td></tr>
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">料金：</td><td style="padding: 5px 0;"><strong>{{ $data['purchase_price'] }}</strong></td></tr>
<tr><td style="padding: 5px 15px 5px 0; color: #6b7280;">種類：</td><td style="padding: 5px 0;">{{ $data['purchase_type'] === 'plan' ? 'プラン' : '商品' }}</td></tr>
</table>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
【アクティベーションコード】<br>
購入した商品/プランをご利用いただくには、以下のアクティベーションコードが必要です。<br>
<br>
<div style="background-color: #f3f4f6; padding: 20px; text-align: center; border-radius: 8px; margin: 15px 0;">
<strong style="font-size: 28px; color: #2563eb; letter-spacing: 4px; font-family: monospace;">{{ implode('-', str_split($data['activation_code'], 4)) }}</strong>
</div>
<br>
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
【アクティベーション手順】<br>
<ol style="margin: 10px 0; padding-left: 20px;">
<li style="margin: 8px 0;"><strong>新規会員登録を行う</strong><br>
<span style="color: #6b7280;">まだ会員登録がお済みでない場合は、以下のリンクから会員登録を行ってください。</span><br>
<a href="{{ $data['registration_url'] }}" style="color: #2563eb; text-decoration: underline;">{{ $data['registration_url'] }}</a></li>
<li style="margin: 8px 0;"><strong>ログイン後、アクティベーション画面へ</strong><br>
<span style="color: #6b7280;">会員登録・ログイン後、サイドメニューまたはダッシュボードの「アクティベーション」から画面へ進んでください。</span></li>
<li style="margin: 8px 0;"><strong>アクティベーションコードを入力</strong><br>
<span style="color: #6b7280;">上記のコードを入力して「有効化」ボタンをクリックしてください。</span></li>
<li style="margin: 8px 0;"><strong>完了！</strong><br>
<span style="color: #6b7280;">購入した商品/プランの機能がご利用いただけるようになります。</span></li>
</ol>
<br>
<div style="background-color: #fef3c7; padding: 15px; border-radius: 8px; border-left: 4px solid #f59e0b; margin: 15px 0;">
<strong style="color: #92400e;">⚠️ 重要</strong><br>
<span style="color: #92400e;">・このコードの有効期限は <strong>{{ $data['expires_at'] }}</strong> です。<br>
・コードは一度のみ使用可能です。</span>
</div>
<br>
@if(!empty($data['invoice_url']))
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
【お支払い明細・領収書】<br>
お支払いの詳細は、以下のリンクからご確認いただけます。<br>
<a href="{{ $data['invoice_url'] }}" style="color: #2563eb; text-decoration: underline;">お支払い明細を確認する</a><br>
<br>
@endif
<hr style="border: none; border-top: 1px solid #e5e7eb; margin: 20px 0;">
<br>
ご不明な点がありましたら、お気軽にお問い合わせください。<br>
<br>
今後とも{{ $data['app_name'] }}をよろしくお願いいたします。<br>
<br>
<span style="color: #6b7280; font-size: 12px;">※このメールは自動送信されています。</span><br>