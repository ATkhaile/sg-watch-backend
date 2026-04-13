<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>HOA DON - {{ $order['order_number'] }}</title>
    <style>
        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 10pt;
            color: #333333;
        }

        table {
            border-collapse: collapse;
        }

        .product-table th {
            background-color: #2c2c2c;
            color: #ffffff;
            padding: 10px 8px;
            font-size: 9pt;
        }

        .product-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #dddddd;
            font-size: 9pt;
        }
    </style>
</head>
<body>

    {{-- Header --}}
    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <img src="{{ public_path('logo.png') }}" style="width: 120px; height: auto;" alt="SGWATCH">
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right;">
                <div style="font-size: 9pt; color: #555555;">Mã số thuế: T3120003020278</div>
                <div style="font-size: 10pt; color: #333333; font-weight: bold;">Số: {{ $order['order_number'] }}</div>
            </td>
        </tr>
    </table>

    {{-- Title --}}
    <div style="text-align: center; margin-top: 20px; margin-bottom: 5px;">
        <div style="font-size: 22pt; font-weight: bold; color: #1a1a1a;">HOÁ ĐƠN</div>
        <div style="font-size: 11pt; color: #555555; margin-top: 5px;">THÔNG TIN BẢO HÀNH</div>
    </div>

    {{-- Gold Line --}}
    <div style="height: 3px; background-color: #C5A55A; margin: 15px 0;">&nbsp;</div>

    {{-- Buyer & Warranty Info --}}
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 140px; font-weight: bold; color: #444444; padding: 4px 0;">Tên người mua:</td>
            <td style="color: #222222; padding: 4px 0;">{{ $order['shipping_name'] }}</td>
        </tr>
        <tr>
            <td style="width: 140px; font-weight: bold; color: #444444; padding: 4px 0;">Bảo hành:</td>
            <td style="color: #222222; padding: 4px 0;">{{ $warranty_text }} tại NHẬT - VIỆT</td>
        </tr>
        <tr>
            <td style="width: 140px; font-weight: bold; color: #444444; padding: 4px 0;">Từ ngày:</td>
            <td style="color: #222222; padding: 4px 0;">{{ $warranty_from }}</td>
        </tr>
        <tr>
            <td style="width: 140px; font-weight: bold; color: #444444; padding: 4px 0;">Đến ngày:</td>
            <td style="color: #222222; padding: 4px 0;">{{ $warranty_to }}</td>
        </tr>
    </table>

    {{-- Product Table --}}
    <table class="product-table" style="width: 100%; margin-bottom: 15px;">
        <thead>
            <tr>
                <th style="width: 50%; text-align: left;">Tên Sản Phẩm</th>
                <th style="width: 12%; text-align: right;">Số Lượng</th>
                <th style="width: 19%; text-align: right;">Đơn Giá</th>
                <th style="width: 19%; text-align: right;">Thành Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td style="text-align: left;">{{ $item['product_name'] }}</td>
                <td style="text-align: right;">{{ $item['quantity'] }}</td>
                <td style="text-align: right;">¥{{ number_format($item['unit_price_jpy'], 0, ',', '.') }}</td>
                <td style="text-align: right;">¥{{ number_format($item['total_price_jpy'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            {{-- Shipping Fee --}}
            <tr>
                <td style="text-align: left; font-weight: bold;">Phí vận chuyển</td>
                <td style="text-align: right;"></td>
                <td style="text-align: right;"></td>
                <td style="text-align: right;">¥{{ number_format($shipping_fee_jpy, 0, ',', '.') }}</td>
            </tr>
            {{-- Stripe Transfer Fee --}}
            @if($stripe_fee_jpy > 0)
            <tr>
                <td style="text-align: left; font-weight: bold;">Phí chuyển tiền</td>
                <td style="text-align: right;"></td>
                <td style="text-align: right;"></td>
                <td style="text-align: right;">¥{{ number_format($stripe_fee_jpy, 0, ',', '.') }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    {{-- Total --}}
    <div style="text-align: right; margin-bottom: 20px;">
        <div style="font-size: 11pt; font-weight: bold; color: #333333;">
            Tổng Tiền: <b style="font-size: 14pt; color: #C5A55A;">¥{{ number_format($total_jpy, 0, ',', '.') }}</b>
        </div>
        <div style="font-size: 10pt; color: #555555; margin-top: 5px;">
            Quy đổi VND (×175): <b style="color: #C5A55A;">{{ number_format($total_vnd, 0, ',', '.') }}đ</b>
        </div>
        <div style="font-size: 8pt; color: #888888;">(đã bao gồm thuế)</div>
    </div>

    {{-- Gold Line --}}
    <div style="height: 3px; background-color: #C5A55A; margin: 15px 0;">&nbsp;</div>

    {{-- Stamp --}}
    <div style="text-align: right; margin-top: 30px;">
        <img src="{{ public_path('stamp.png') }}" style="height: 90px; width: auto;" alt="Stamp">
    </div>

    {{-- Thank you --}}
    <div style="margin-top: 20px;">
        <div style="font-size: 10pt; font-weight: bold; color: #333333;">
            Cảm ơn Bạn đã tin tưởng mua hàng tại SGWATCH, mong được phục vụ trong lần tới !
        </div>
    </div>

    {{-- Note --}}
    <div style="margin-top: 10px; font-size: 8pt; color: #555555;">
        Lưu ý: Các trường hợp áp dụng bảo hành xem ở mục chính sách bảo hành !
    </div>

    {{-- Footer --}}
    <div style="margin-top: 30px; padding-top: 10px; border-top: 1px solid #dddddd; text-align: center;">
        <div style="font-size: 9pt; color: #555555;">TEL: 090-3978-1993</div>
        <div style="font-size: 8pt; color: #888888;">〒542-0072 大阪府 大阪市中央区 高津2-8-6 文楽東ビル3F</div>
    </div>

</body>
</html>
