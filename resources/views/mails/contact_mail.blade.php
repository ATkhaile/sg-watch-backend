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
このたびは、{{ $serviceName }}にお問い合わせいただき誠にありがとうございます。<br>
以下の内容でお問い合わせを受け付けいたしました。<br>
<br>
お名前: {{$data['last_name_kanji']}}{{$data['first_name_kanji']}}<br>
メールアドレス:{{$data['email']}}<br>
お問い合わせ種別:{{$data['contact_type_text']}}<br>
お問い合わせ内容:<br>
{!! nl2br($data['content']) !!} <br>
ご入力いただいた内容を確認の上、担当者よりご連絡いたします。<br>
お問い合わせ内容によっては、ご返信にお時間をいただく場合がございますので、あらかじめご了承ください。<br><br>
なお、本メールはお問い合わせ受付時に自動送信されたものです。<br>
本メールに返信いただいても対応いたしかねますので、予めご了承ください。<br>
<br>
引き続き、どうぞよろしくお願い申し上げます。<br>
<br>
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
