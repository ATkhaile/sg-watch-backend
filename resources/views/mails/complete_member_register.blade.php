@php
$serviceName = env('MEMBER_SERVICE_NAME', 'Booking Service');
$serviceUrl = env('MEMBER_SERVICE_URL', '');
$serviceCompany = env('MEMBER_SERVICE_COMPANY', '');
$serviceAddress = env('MEMBER_SERVICE_ADDRESS', '');
@endphp
※このメールはシステムからの自動返信です
<br>
<br>
{{$data['last_name_kanji']}}{{$data['first_name_kanji']}}様<br>
<br>
お世話になっております。
<br>
この度は{{ $serviceName }}に会員登録をいただきありがとうございます。<br>
このメールは、ご登録時に確認のため送信させていただいております。<br>
<br>
<hr>
<br>
■ご登録いただいたメールアドレス<br>
<hr>
<br>
メールアドレス：{{$data['email']}}<br>
<br><br>
ログインはLINEアカウントを使用します。<br>
ログイン時は画面に従い、LINEアカウントを使用しログインしてください。<br>
<br>
▼▼マイページで確認する▼▼<br>
{{ $serviceUrl }}/profile<br>
<br>
▼▼早速予約をする▼▼<br>
{{ $serviceUrl }}<br>
<br>
{{$data['last_name_kanji']}}{{$data['first_name_kanji']}}様のご利用を心よりお待ち申し上げます。<br>
また、ご不明な点がございましたら<br>
下記までお気軽にお問い合せくださいませ。<br>
<br>
<hr>
<br>
{{ $serviceName }}<br>
{{ $serviceUrl }}<br>
<br>
【運営元：{{ $serviceCompany }}】<br>
{{ $serviceAddress }}<br>
<br>
このEメールアドレスは配信専用です。<br>
このメッセージにはご返信いただけません。<br>
<br>
お問い合わせは、ウェブサイト内の<br>
お問い合わせフォームよりお願いいたします。<br>
{{ $serviceUrl }}/contact<br>

<hr>
