<?php

namespace App\Http\Actions\Api\ShopOrder;

use App\Enums\OrderStatus;
use App\Http\Controllers\BaseController;
use App\Models\Shop\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mpdf\Mpdf;

class AdminGenerateInvoiceAction extends BaseController
{
    public function __invoke(int $id): Response|JsonResponse
    {
        $order = Order::where('id', $id)
            ->whereIn('status', [OrderStatus::DELIVERED, OrderStatus::COMPLETED])
            ->with(['items.product:id,warranty_months'])
            ->first();

        if (!$order) {
            return response()->json([
                'message' => 'Order not found or not eligible for invoice.',
                'status_code' => 404,
                'data' => null,
            ], 404);
        }

        return $this->generatePdf($order);
    }

    private function generatePdf(Order $order): Response
    {
        $conversionRate = 175;
        $isJpy = $order->currency === 'JPY';

        $items = $order->items->map(function ($item) use ($isJpy, $conversionRate) {
            $unitPriceJpy = $isJpy ? (int) $item->unit_price : (int) round($item->unit_price / $conversionRate);
            $totalPriceJpy = $isJpy ? (int) $item->total_price : (int) round($item->total_price / $conversionRate);
            return [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'unit_price_jpy' => $unitPriceJpy,
                'total_price_jpy' => $totalPriceJpy,
                'warranty_months' => $item->product?->warranty_months ?? 0,
            ];
        })->toArray();

        $shippingFeeJpy = $isJpy ? (int) $order->shipping_fee : (int) round($order->shipping_fee / $conversionRate);
        $subtotalJpy = array_sum(array_column($items, 'total_price_jpy'));
        $totalJpy = $subtotalJpy + $shippingFeeJpy;
        $totalVnd = $totalJpy * $conversionRate;

        $maxWarrantyMonths = max(array_column($items, 'warranty_months') ?: [0]);
        $warrantyYears = (int) floor($maxWarrantyMonths / 12);
        $warrantyRemainingMonths = $maxWarrantyMonths % 12;

        if ($warrantyYears > 0 && $warrantyRemainingMonths > 0) {
            $warrantyText = sprintf('%02d Năm %d Tháng', $warrantyYears, $warrantyRemainingMonths);
        } elseif ($warrantyYears > 0) {
            $warrantyText = sprintf('%02d Năm', $warrantyYears);
        } elseif ($warrantyRemainingMonths > 0) {
            $warrantyText = sprintf('%02d Tháng', $warrantyRemainingMonths);
        } else {
            $warrantyText = 'Không có';
        }

        $warrantyFromDate = $order->delivered_at ?? $order->created_at;
        $warrantyToDate = $warrantyFromDate->copy()->addMonths($maxWarrantyMonths);

        $data = [
            'order' => $order->toArray(),
            'items' => $items,
            'subtotal_jpy' => $subtotalJpy,
            'shipping_fee_jpy' => $shippingFeeJpy,
            'total_jpy' => $totalJpy,
            'total_vnd' => $totalVnd,
            'warranty_text' => $warrantyText,
            'warranty_from' => $warrantyFromDate->format('d/m/Y'),
            'warranty_to' => $warrantyToDate->format('d/m/Y'),
        ];

        $html = view('pdf.invoice', $data)->render();

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'margin_left' => 20,
            'margin_right' => 20,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);

        $mpdf->WriteHTML($html);

        $filename = 'invoice_' . $order->order_number . '.pdf';

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
