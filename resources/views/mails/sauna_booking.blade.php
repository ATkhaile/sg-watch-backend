@php
$serviceName = env('MEMBER_SERVICE_NAME', 'Booking Service');
$serviceUrl = env('MEMBER_SERVICE_URL', '');
$serviceCompany = env('MEMBER_SERVICE_COMPANY', '');
$serviceAddress = env('MEMBER_SERVICE_ADDRESS', '');
@endphp
※このメールはシステムからの自動返信です<br>
<br>
{{$data['last_name_kanji']}}{{$data['first_name_kanji']}}様<br>
<br>
いつも「{{ $serviceName }}」をご利用いただきまして<br>
誠にありがとうございます。<br>
<br>
以下の内容でご予約を受け付けました。<br>
下記予約内容をご確認いただき、そのままご予約の日時にご来店ください。<br>
※本メールは配信専用のため、ご返信いただきましても各店舗へは届きません。<br>
<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
■ご予約内容：ご利用店舗 {{ $data['shop_info']->name }}<br>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━<br>
予約番号：{{ $data['reservation_number'] }}<br>
予約日時：{{ $data['date'] }}<br>
<br>
■利用時間：{{ $data['usage_option_name'] ?? '' }}<br>
■オプション：{{ $data['option1_name'] }}<br>
■オプション：{{ $data['option2_name'] }}<br>
■駐車場のご利用：{{ $data['parking_flag'] ? 'あり' : 'なし' }}<br>
■クーポンのご利用：{{ $data['coupon_name'] }}<br>
<br>
■お支払い方法：クレジットカード<br>
■お支払い金額：¥{{ $data['total_amount'] }}<br>
@if (isset($data['receipt_url']) && $data['receipt_url'])
{{ $data['receipt_url'] }}<br>
@endif
<br>
▼▼マイページで確認する▼▼<br>
{{ $serviceUrl }}/profile<br>
<br>
鍵の解除番号：{{$data['pin']}}<br>
<br>
必ずご確認いただき、現地でロックを解除してください。<br>
解錠番号は、利用開始時間の5分前〜利用終了時間まで有効です。<br>
<br>
また、ご不明な点がございましたら<br>
下記までお気軽にお問い合せくださいませ。<br>
<br>
———————————————————————<br>
ご利用店舗：<br>
{{ $data['shop_info']->name }}<br>
<br>
住所：大阪府東大阪市御厨4-6-20（GoogleMap）<br>
駐車場をご利用される場合は下記マップ「37番」をご利用ください。<br>
maps.app.goo.gl/frx4b6adMAn5KMA48<br>
お問い合わせ 公式LINE：https://page.line.me/271szvos<br>
店舗ページ：{{ $serviceUrl }}/sauna/top<br>
店舗ウェブサイト：https://www.saunauri.com/<br>
———————————————————————<br>
{{ $serviceName }}<br>
{{ $serviceUrl }}<br>
<br>
【運営元：{{ $serviceCompany }}】<br>
{{ $serviceAddress }}<br>
<br>
このEメールアドレスは配信専用です。<br>
このメッセージにはご返信いただけません。<br>
<br>
予約内容に関するお問い合わせは、<br>
各店舗の公式LINEへご連絡をお願いします。<br>
———————————————————————
