<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StripeTransactionsExport implements FromArray, WithHeadings, WithStyles
{
    protected $transactions;

    public function __construct(array $transactions)
    {
        $this->transactions = $transactions;
    }

    public function array(): array
    {
        $rows = [];

        foreach ($this->transactions as $transaction) {
            $rows[] = [
                'id' => $transaction['id'] ?? '',
                'created' => isset($transaction['created']) ? date('Y-m-d H:i:s', $transaction['created']) : '',
                'customer_name' => $transaction['user_info']['user_name'] ?? ($transaction['customer'] ?? 'ゲスト'),
                'customer_email' => $transaction['user_info']['user_email'] ?? ($transaction['receipt_email'] ?? '-'),
                'plan_name' => $transaction['user_info']['plan_name'] ?? ($transaction['description'] ?? '-'),
                'amount' => $transaction['amount'] ?? 0,
                'amount_received' => $transaction['amount_received'] ?? 0,
                'currency' => strtoupper($transaction['currency'] ?? 'JPY'),
                'status' => $this->translateStatus($transaction['status'] ?? ''),
                'payment_method' => $transaction['payment_method'] ?? '-',
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            '日時',
            '顧客名',
            'メールアドレス',
            'プラン',
            '金額',
            '受取金額',
            '通貨',
            '状態',
            '決済方法',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    private function translateStatus(string $status): string
    {
        $translations = [
            'succeeded' => '成功',
            'processing' => '処理中',
            'requires_payment_method' => '支払い方法が必要',
            'requires_confirmation' => '確認が必要',
            'requires_action' => 'アクションが必要',
            'canceled' => 'キャンセル',
            'failed' => '失敗',
        ];

        return $translations[$status] ?? $status;
    }
}
